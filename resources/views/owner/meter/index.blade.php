@extends('owner.layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- Page Content Wrapper Start -->
                <div class="page-content-wrapper bg-white p-30 radius-20">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div
                                class="page-title-box d-sm-flex align-items-center justify-content-between border-bottom mb-20">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="page-title-left me-sm-4">
                                        <h3 class="mb-sm-0">{{ $pageTitle }} </h3>
                                    </div>
                                    <!-- Add property button -->
                                    <a href="" id="add_meter_btn" class="theme-btn mt-2 mt-sm-0"
                                        title="{{ __('All Meters') }}">
                                        {{ __('Add New Meters') }}
                                    </a>
                                </div>
                                <div class="page-title-right mt-3 mt-sm-0">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('owner.dashboard') }}" title="{{ __('Dashboard') }}">
                                                {{ __('Dashboard') }}
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ __('All Meters') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- end page title -->

                    <!-- All Property Area row Start -->
                    <div class="billing-center-area bg-off-white theme-border radius-4 p-25">
                        <!-- datatable Start -->
                        <table id="meterTable" class="table responsive theme-border p-20 dataTable no-footer dtr-inline"
                            role="grid">
                            <thead>
                                <tr>
                                    <th>{{ __('Serial Number') }}</th>
                                    <th>{{ __('Meter Type') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Count') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- All Property Area row End -->
                </div>
                <!-- Page Content Wrapper End -->
            </div>
        </div>
        <!-- End Page-content -->
    </div>
    <input type="hidden" name="" id="meterDataRoute" value="{{ route('owner.meter.all') }}">

    <!-- model to add new meter -->
    <div class="modal fade " id="informationModal" tabindex="-1" aria-labelledby="informationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="informationModalLabel">{{ __('Add Meter') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="" action="{{ route('owner.meter.store') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        <h4 class="mb-15">{{ __('Meter Details') }}</h4>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Serialnumber') }}
                                    </label>
                                    <input type="text" name="serial_number" class="form-control"
                                        placeholder="--{{ __('Input Serialnumber') }}--">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Property') }}
                                    </label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id">
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Unit') }}
                                    </label>
                                    <select class="form-select flex-shrink-0 unit_id" name="unit_id">
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Meter Type') }}
                                    </label>
                                    <select class="form-select flex-shrink-0" name="meter_type" id="meter_type_select">
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                        <option value="1">Electricity (kWh)</option>
                                        <option value="2">Hot Water (m³)</option>
                                        <option value="3">Cold Water (m³)</option>
                                        <option value="4">Heating (GJ)</option>
                                        <option value="5">Gas (m³)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <h4 class="">{{ __('Meter History') }}</h4>
                            </div>
                            <div class="col">
                                <a id="add-meter-btn" class="label-text-title font-medium" style="cursor: pointer;">
                                    <i class="ri-add-circle-fill"></i> Add New Meter Count
                                </a>
                            </div>
                        </div>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 "
                            id="meter-form-container">
                            <div class="row meter-form">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3 mb-25">
                                            <label class="label-text-title font-medium mb-2">{{ __('Meter Date') }}</label>
                                            <input type="date" name="meter_date[]" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-25">
                                            <label class="label-text-title font-medium mb-2">{{ __('Meter Count') }}</label>
                                            <input type="text" 
                                                   name="meter_count[]" 
                                                   class="form-control" 
                                                   pattern="[0-9]*[,.]?[0-9]+"
                                                   placeholder="{{ __('xxxx,xxx') }}"
                                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 44 || event.charCode === 46"
                                                   oninput="this.value = this.value.replace(/[^0-9,\.]/g, '');">
                                        </div>
                                        <div class="col-md-3 mb-25">
                                            <label
                                                class="label-text-title font-medium mb-2 ">{{ __('Meter Unit') }}</label>
                                            <input type="text" name="meter_unit[]"
                                                class="form-control meter_unit_input" readonly
                                                style="background: #e9ecef;">
                                        </div>
                                        <div class="col-md-3 mb-25">
                                            <label
                                                class="label-text-title font-medium mb-2">{{ __('Meter Picture') }}</label>
                                            <div class="d-flex align-items-center">
                                                <input type="hidden" name="meter_picture[]" class="meter-picture-input">
                                                <input type="text" class="form-control image-placeholder" disabled>
                                                <img src="" alt="Selected Image" class="img-preview"
                                                    style="display:none; width: 100px; height: 50px; object-fit: cover; border: 1px solid #ccc;">
                                                <input type="file" class="d-none file-input" accept="image/*">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-2" style="display: flex; align-items: center;">
                                    <button type="button" class="p-1 tbl-action-btn"><i
                                            class="ri-upload-2-fill"></i></button>
                                    <button type="button" class="p-1 tbl-action-btn remove-meter-row" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            class="iconify iconify--ep" width="1em" height="1em"
                                            viewBox="0 0 1024 1024">
                                            <path fill="currentColor"
                                                d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Submit') }}">{{ __('Submit') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- model to edit the meter -->
    <div class="modal fade " id="editMeterModel" tabindex="-1" aria-labelledby="informationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="informationModalLabel">{{ __('Edit Meter') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="" action="{{ route('owner.meter.update') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="edit_meter_id">
                        <h4 class="mb-15">{{ __('Meter Details') }}</h4>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Serialnumber') }}
                                    </label>
                                    <input type="text" name="serial_number" class="form-control"
                                        id="edit_meter_serial_number" placeholder="--{{ __('Input Serialnumber') }}--">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Property') }}
                                    </label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id"
                                        id="edit_property_type">
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Unit') }}
                                    </label>
                                    <select class="form-select flex-shrink-0 unit_id" name="unit_id" id="edit_unit_type">
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Meter Type') }}
                                    </label>
                                    <select class="form-select flex-shrink-0" name="meter_type" id="edit_meter_type">
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                        <option value="1">Electricity (kWh)</option>
                                        <option value="2">Hot Water (m³)</option>
                                        <option value="3">Cold Water (m³)</option>
                                        <option value="4">Heating (GJ)</option>
                                        <option value="5">Gas (m³)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <h4 class="">{{ __('Meter History') }}</h4>
                            </div>
                            <div class="col">
                                <a id="add-meter-btn-edit" class="label-text-title font-medium" style="cursor: pointer;">
                                    <i class="ri-add-circle-fill"></i> Add New Meter Count
                                </a>
                            </div>
                        </div>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 " id="edit-meter-form-container">
                            <div class="row edit-meter-form">
                                <div class="col-md-10">
                                   
                                </div>
                                <div class="col-md-2" style="display: flex; align-items: center;">
                                    <button type="button" class="p-1 tbl-action-btn"><i
                                            class="ri-upload-2-fill"></i></button>
                                    <button type="button" class="p-1 tbl-action-btn remove-meter-row-editmodel"
                                        title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            class="iconify iconify--ep" width="1em" height="1em"
                                            viewBox="0 0 1024 1024">
                                            <path fill="currentColor"
                                                d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="theme-btn-back me-3" data-bs-dismiss="modal"
                            title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit" class="theme-btn me-3"
                            title="{{ __('Submit') }}">{{ __('Update Meter') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- model to view the meter -->
    <div class="modal fade " id="viewMeterDetailsModel" tabindex="-1" aria-labelledby="informationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="informationModalLabel">{{ __('View Meter') }}</h4>
                    <button class="theme-btn mt-2 mt-sm-0" style="margin-left: 20px"
                        id="update_meter_btn">{{ __('Update Meter') }}</button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <form class="ajax" action="{{ route('owner.meter.store') }}" method="POST"
                    enctype="multipart/form-data" data-handler="getShowMessage">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="" id="meter_id_for_edit">
                        <h4 class="mb-15">{{ __('Meter Details') }}</h4>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20">
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Serialnumber') }}
                                    </label>
                                    <input type="text" name="serial_number" class="form-control"
                                        id="view_serial_number" placeholder="--{{ __('Input Serialnumber') }}--"
                                        disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Property') }}
                                    </label>
                                    <select class="form-select flex-shrink-0 property_id" name="property_id"
                                        id="view_property_type" disabled>
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Unit') }}
                                    </label>
                                    <select class="form-select flex-shrink-0 unit_id" name="unit_id" id="view_unit_type"
                                        disabled>
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-25">
                                    <label class="label-text-title  font-medium mb-2">{{ __('Meter Type') }}
                                    </label>
                                    <select class="form-select flex-shrink-0" name="meter_type" id="view_meter_type"
                                        disabled>
                                        <option value="" selected>--{{ __('Select Option') }}--</option>
                                        <option value="1">Electricity (kWh)</option>
                                        <option value="2">Hot Water (m³)</option>
                                        <option value="3">Cold Water (m³)</option>
                                        <option value="4">Heating (GJ)</option>
                                        <option value="5">Gas (m³)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <h4 class="">{{ __('Meter History') }}</h4>
                            </div>
                            <div class="col">
                            </div>
                        </div>
                        <div class="modal-inner-form-box bg-off-white theme-border radius-4 p-20 "
                            id="meter-form-container1">
                            <div class="row meter-form">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3 mb-25">
                                            <label
                                                class="label-text-title font-medium mb-2">{{ __('Meter Date') }}</label>
                                            <input type="date" name="meter_date[]" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-25">
                                            <label class="label-text-title font-medium mb-2">{{ __('Meter Count') }}</label>
                                            <input type="text" 
                                                   name="meter_count[]" 
                                                   class="form-control" 
                                                   pattern="[0-9]*[,.]?[0-9]+"
                                                   placeholder="{{ __('xxxx,xxx') }}"
                                                   onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 44 || event.charCode === 46"
                                                   oninput="this.value = this.value.replace(/[^0-9,\.]/g, '');">
                                        </div>
                                        <div class="col-md-3 mb-25">
                                            <label
                                                class="label-text-title font-medium mb-2 ">{{ __('Meter Unit') }}</label>
                                            <input type="text" name="meter_unit[]"
                                                class="form-control meter_unit_input" readonly>
                                        </div>
                                        <div class="col-md-3 mb-25">
                                            <label
                                                class="label-text-title font-medium mb-2">{{ __('Meter Picture') }}</label>
                                            <div class="d-flex align-items-center">
                                                <input type="hidden" name="meter_picture[]" class="meter-picture-input">
                                                <input type="text" class="form-control image-placeholder" disabled>
                                                <img src="" alt="Selected Image" class="img-preview"
                                                    style="display:none; width: 100px; height: 50px; object-fit: cover; border: 1px solid #ccc;">
                                                <input type="file" class="d-none file-input" accept="image/*">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-2" style="display: flex; align-items: center;">
                                    <button type="button" class="p-1 tbl-action-btn"><i
                                            class="ri-upload-2-fill"></i></button>
                                    <button type="button" class="p-1 tbl-action-btn remove-meter-row" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            class="iconify iconify--ep" width="1em" height="1em"
                                            viewBox="0 0 1024 1024">
                                            <path fill="currentColor"
                                                d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Modal for Enlarged Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            class="iconify" data-icon="akar-icons:cross"></span></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Enlarged Image">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    @include('common.layouts.datatable-style')
    <style>
        .is-invalid {
            border: 1px solid red !important;
          
            /* Light red background */
        }

        .text-danger {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
    </style>
@endpush
@push('script')
    @include('common.layouts.datatable-script')
    @include('owner.meter.meter-js')
@endpush
