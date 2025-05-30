<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\NoticeBoard;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Ticket;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\TenantService;
use App\Models\EmailTemplate;
use App\Models\FileManager;
use App\Models\TenantDetails;
use App\Services\SmsMail\MailService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;


class MyRentController extends Controller
{
  
   public function index(){
       
       $id = auth()->user()->id;
       $data['pageTitle'] = __('My Rent');
       $data['navTenantHomeActiveClass'] = 'active';
       $data['tenant'] = $this->getDetailsById($id);
      
       return view('tenant.myrent.index', $data);
   }

   public function getDetailsById($id)
   {
       if (auth()->user()->role == USER_ROLE_OWNER) {
           $userId = auth()->id();
       } else {
           $userId = auth()->user()->owner_user_id;
       }
       $tenant = Tenant::where('user_id',$id)->first();
       $tenant_id = $tenant->id;
        try{
            $data = Tenant::query()
                ->join('users', 'tenants.user_id', '=', 'users.id')
                ->whereNull('users.deleted_at')
                ->leftJoin('tenant_details', 'tenants.id', '=', 'tenant_details.tenant_id')
                ->leftJoin('properties', 'tenants.property_id', '=', 'properties.id')
                ->leftJoin('property_details', 'properties.id', '=', 'property_details.property_id')
                ->leftJoin('property_units', 'tenants.unit_id', '=', 'property_units.id')
                ->select(['tenants.*', 'users.first_name', 'users.last_name', 'users.contact_number', 'users.email','users.nid_number','users.date_of_birth','property_units.unit_name','property_units.keycode', 'properties.name as property_name', 'property_details.address as property_address', 'tenant_details.previous_address', 'tenant_details.previous_country_id', 'tenant_details.previous_state_id', 'tenant_details.previous_city_id', 'tenant_details.previous_zip_code', 'tenant_details.permanent_address', 'tenant_details.permanent_country_id', 'tenant_details.permanent_state_id', 'tenant_details.permanent_city_id', 'tenant_details.permanent_zip_code'])
                ->where('tenants.owner_user_id', $userId)
                ->where('tenants.id', $tenant_id)
                ->first();
            return $data?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        }catch(\Exception $e){
            return $e->getMessage();
        }
   }

}
