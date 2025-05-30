<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\PropertyService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\Meter;
use App\Models\MeterHistory;
use App\Models\FileManager;
use App\Models\Property;
use App\Models\PropertyUnit;
use Illuminate\Support\Facades\DB;
use LDAP\Result;
use PDO;

class MeterController extends Controller
{
    use ResponseTrait;
    public $propertyService;
    public function __construct()
    {
        $this->propertyService = new PropertyService;
    }

    public function index()
    {
        $data['pageTitle'] = __('All Meters');
        $data['properties'] = $this->propertyService->getAll();;
        return view('owner.meter.index')->with($data);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            DB::beginTransaction(); // Start transaction

            // Create the meter record
            $meter = Meter::create([
                'serial_number' => $request->serial_number, // Fixed typo
                'property_id' => $request->property_id,
                'unit_id' => $request->unit_id,
                'meter_type' => $request->meter_type,
            ]);

            \Log::info('Meter Created:', ['meter' => $meter]); // Debugging log

            // Loop through meter history data
            foreach ($request->meter_date as $key => $date) {
                $meterHistory = new MeterHistory();
                $meterHistory->meter_id = $meter->id;
                $meterHistory->date = $date;
                $meterHistory->count = $request->meter_count[$key];
                $meterHistory->unit = $request->meter_unit[$key];

                // Handle base64 image upload if exists
                if (!empty($request->meter_picture[$key])) {
                    $base64Image = $request->meter_picture[$key];

                    // Extract the base64 string
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
                        $imageType = $matches[1]; // Get image extension
                        $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                        $base64Image = base64_decode($base64Image);

                        if ($base64Image === false) {
                            throw new \Exception("Invalid base64 image data.");
                        }

                        // Create a temporary file
                        $tempFilePath = tempnam(sys_get_temp_dir(), 'meter_image_') . ".$imageType";
                        file_put_contents($tempFilePath, $base64Image);

                        // Convert the temp file into an uploaded file instance
                        $uploadedFile = new \Illuminate\Http\UploadedFile(
                            $tempFilePath,
                            "meter_image.$imageType",
                            mime_content_type($tempFilePath),
                            null,
                            true
                        );

                        // Upload to file manager
                        $new_file = new FileManager();
                        $upload = $new_file->upload('MeterImage', $uploadedFile);

                        if ($upload['status']) {
                            $meterHistory->image = $upload['file']->id;

                            // Update file's origin details
                            $upload['file']->origin_id = $meterHistory->id;
                            $upload['file']->origin_type = "App\Models\MeterHistory";
                            $upload['file']->save();
                        } else {
                            throw new \Exception($upload['message']);
                        }

                        // Delete temp file after upload
                        unlink($tempFilePath);
                    } else {
                        throw new \Exception("Invalid base64 image format.");
                    }
                }

                $meterHistory->save();
            }


            DB::commit();
            // return response()->json(['success' => true, 'message' => __('Uploaded_Successfully'), 'data' => $meter]);
            return redirect()->back()->with('success', __('Uploaded_Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Meter Store Error:', ['error' => $e->getMessage()]); // Log the error
            // return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getAll(Request $request)
    {
        $meters = Meter::query()
            ->join('properties', 'meters.property_id', '=', 'properties.id')
            ->join('property_units', 'meters.unit_id', '=', 'property_units.id')
            ->leftJoin('meter_histories', function ($join) {
                $join->on('meters.id', '=', 'meter_histories.meter_id')
                    ->whereRaw('meter_histories.date = (SELECT MAX(date) FROM meter_histories WHERE meter_id = meters.id)');
            })
            ->select(
                'meters.id', // Add this line
                'meters.serial_number',
                'meters.meter_type',
                'properties.name as property',
                'meter_histories.count',
                'meter_histories.unit',
                'property_units.unit_name as unitname',
            );

        if ($request->has('property') && !empty($request->property)) {
            $meters->where('meters.property_id', $request->property);
        }
        if ($request->has('unit') && !empty($request->unit)) {
            $meters->where('meters.unit_id', $request->unit);
        }
        if ($request->has('meter_type') && !empty($request->meter_type)) {
            $meters->where('meters.meter_type', $request->meter_type);
        }
        return datatables($meters)
        ->addColumn('action', function ($meter) {
            return '<button class="p-1 tbl-action-btn view view_meter_details" data-id="' . $meter->id . '"> 
                        <svg xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                            role="img" class="iconify iconify--carbon" width="1em"
                            height="1em" preserveAspectRatio="xMidYMid meet"
                            viewBox="0 0 32 32" data-icon="carbon:view-filled">
                            <circle cx="16" cy="16" r="4" fill="currentColor"></circle>
                            <path fill="currentColor" d="M30.94 15.66A16.69 16.69 0 0 0 16 5A16.69 16.69 0 0 0 1.06 15.66a1 1 0 0 0 0 .68A16.69 16.69 0 0 0 16 27a16.69 16.69 0 0 0 14.94-10.66a1 1 0 0 0 0-.68M16 22.5a6.5 6.5 0 1 1 6.5-6.5a6.51 6.51 0 0 1-6.5 6.5">
                            </path>
                        </svg>
                    </button>
    
                    <button class="delete_btn p-1 tbl-action-btn" delete="' . $meter->id . '" 
                       
                        title="' . __('Delete') . '">
                        <span class="iconify" data-icon="ep:delete-filled"></span>
                    </button>';
        })
        ->rawColumns(['action'])
        ->make(true);
    
    }

    public function delete($id)
    {
        try {
            $meter = Meter::find($id);
    
            if (!$meter) {
                return response()->json(['success' => false, 'message' => 'Meter not found'], 404);
            }
    
            // Delete all related Meter History records
            MeterHistory::where('meter_id', $id)->delete();
            
            // Delete the meter
            $meter->delete();
    
            return response()->json(['success' => true, 'message' => __('Deleted Successfully')], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    

    public function details($id)
    {

        $meter = Meter::find($id);
        $histories = MeterHistory::where('meter_id', $id)
            ->leftJoin('file_managers', 'meter_histories.image', '=', 'file_managers.id')
            ->select(
                'meter_histories.*',
                'file_managers.folder_name',
                'file_managers.file_name',
                'file_managers.origin_type',
                'file_managers.origin_id'
            )
            ->orderByDesc('meter_histories.date') // Ensures latest date comes first
            ->get()
            ->map(function ($history) {
                // Append the asset URL properly
                if (!empty($history->folder_name) && !empty($history->file_name)) {
                    $history->asset_url = asset('storage/' . $history->folder_name . '/' . $history->file_name);
                } else {
                    $history->asset_url = null; // Handle missing images
                }
                return $history;
            });



        $properties = Property::all();
        $units = PropertyUnit::where('property_id', $meter->property_id)->get();
        return response()->json([
            'meter' => $meter,
            'histories' => $histories,
            'properties' => $properties,
            'units' => $units,
            'id' => $id,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $meter = Meter::find($request->id);
            $histories = MeterHistory::where('meter_id', $request->id)->get();
            foreach ($histories as $history) {
                $history->delete();
            }

            $meter->update([
                'serial_number' => $request->serial_number, // Fixed typo
                'property_id' => $request->property_id,
                'unit_id' => $request->unit_id,
                'meter_type' => $request->meter_type,
            ]);

            // Loop through meter history data
            foreach ($request->meter_date as $key => $date) {
                $meterHistory = new MeterHistory();
                $meterHistory->meter_id = $meter->id;
                $meterHistory->date = $date;
                $meterHistory->count = $request->meter_count[$key];
                $meterHistory->unit = $request->meter_unit[$key];

                // Check if meter_picture is an ID or a base64 string
                if (!empty($request->meter_picture[$key])) {
                    $meterPicture = $request->meter_picture[$key];

                    if (is_numeric($meterPicture)) {
                        // If it's a number, store it directly as image ID
                        $meterHistory->image = $meterPicture;
                    } elseif (preg_match('/^data:image\/(\w+);base64,/', $meterPicture, $matches)) {
                        // If it's a base64 image, process and upload it
                        $imageType = $matches[1]; // Get image extension
                        $base64Image = substr($meterPicture, strpos($meterPicture, ',') + 1);
                        $base64Image = base64_decode($base64Image);

                        if ($base64Image === false) {
                            throw new \Exception("Invalid base64 image data.");
                        }

                        // Create a temporary file
                        $tempFilePath = tempnam(sys_get_temp_dir(), 'meter_image_') . ".$imageType";
                        file_put_contents($tempFilePath, $base64Image);

                        // Convert the temp file into an uploaded file instance
                        $uploadedFile = new \Illuminate\Http\UploadedFile(
                            $tempFilePath,
                            "meter_image.$imageType",
                            mime_content_type($tempFilePath),
                            null,
                            true
                        );

                        // Upload to file manager
                        $new_file = new FileManager();
                        $upload = $new_file->upload('MeterImage', $uploadedFile);

                        if ($upload['status']) {
                            $meterHistory->image = $upload['file']->id;

                            // Update file's origin details
                            $upload['file']->origin_id = $meterHistory->id;
                            $upload['file']->origin_type = "App\Models\MeterHistory";
                            $upload['file']->save();
                        } else {
                            throw new \Exception($upload['message']);
                        }

                        // Delete temp file after upload
                        unlink($tempFilePath);
                    } else {
                        throw new \Exception("Invalid meter_picture format.");
                    }
                }

                $meterHistory->save();
            }
            // return response()->json(['success' => true, 'message' => __('Updated_Successfully'), 'data' => $meter]);
            return redirect()->back()->with('success', __('Updated_Successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
