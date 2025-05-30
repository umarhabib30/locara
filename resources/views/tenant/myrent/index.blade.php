@extends('tenant.layouts.app')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="page-content-wrapper bg-white p-30 radius-20">
                <div class="row">
                    <div class="col-12">
                        <div
                            class="page-title-box d-sm-flex align-items-center justify-content-between border-bottom mb-20">
                            <div class="page-title-left">
                                <h3 class="mb-sm-0">{{ $pageTitle }}</h3>
                            </div>
                            <div class="page-title-right">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}"
                                            title="{{ __('Dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                        <div class=" bg-off-white theme-border radius-4 p-25">
                            <!-- Tenants Home Details Start -->
                            <div class="tenants-home-details-information">
                                <!-- Account Settings Content Box Start -->
                                <div class="account-settings-content-box">
                                    <div class="account-settings-info-box">
                                        <!-- Property Item Start -->
                                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                            <div class="property-item tenants-details-home-details-property-item bg-off-white border-bottom radius-10 radius-b-l-0 radius-b-r-0 mb-25">
                                                <a href="#"  class="property-item-img-wrap d-block position-relative overflow-hidden radius-10">
                                                    <div class="property-item-img">
                                                        <img src="{{ $tenant->property?->thumbnail_image }}"
                                                            alt="" class="fit-image">
                                                    </div>
                                                </a>
                                                <div class="property-item-content p-25 px-0">
                    
                                                    <div class="tenants-details-property-info-left">
                                                        <h3 class="property-item-title">
                                                            <a href="#"
                                                                class="color-heading link-hover-effect">{{ $tenant->property_name }}</a>
                                                        </h3>
                                                        <div class="property-item-address d-flex mt-15">
                                                            <div class="flex-shrink-0 font-13">
                                                                <i class="ri-map-pin-2-fill"></i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-1">
                                                                <p>{{ $tenant->property_address }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="property-item-info mt-15 bg-white theme-border py-3 px-2 radius-4">
                                                            <div class="row">
                                                                <div class="col-sm-6 col-md-6">
                                                                    <div
                                                                        class="property-info-item property-info-item-left font-14">
                                                                        <i
                                                                            class="ri-home-5-fill me-1 "></i>{{ $tenant->unit_name }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6 col-md-6">
                                                                    <div
                                                                        class="property-info-item property-info-item-right font-14">
                                                                        <i
                                                                            class="ri-checkbox-circle-fill me-1 "></i>
                                                                        @if ($tenant->status == TENANT_STATUS_ACTIVE)
                                                                        {{ __('Currnetly Tenant') }}
                                                                        @else
                                                                        {{ __('Deactivate Tenant') }}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tenants-details-home-details-lease-date mt-15">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6><span
                                                                            class="theme-text-color me-2">{{ __('Lease Start Date') }}:</span>{{ $tenant->lease_start_date }}
                                                                    </h6>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6><span
                                                                            class="theme-text-color me-2">{{ __('Lease End Date') }}:</span>{{ $tenant->lease_end_date ?? __('Unlimited') }}
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Property Item End -->
                    
                                        <!-- Tenants Details Home Details Edit Rent Information Start -->
                                        <div class="add-property-title border-bottom pb-20 mb-25">
                                            <h4>{{ __('Rent Information') }}</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-4 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('General Rent') }}</label>
                                                <div class="input-group custom-input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('General Rent') }}"
                                                        value="{{ $tenant->general_rent }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-4 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Security Deposit') }}</label>
                                                <div class="input-group custom-input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Security Deposit') }}"
                                                        value="{{ $tenant->security_deposit_type == TYPE_FIXED ? $tenant->security_deposit : $tenant->general_rent + $tenant->general_rent * $tenant->security_deposit * 0.01 }}"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-4 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Late Fee') }}</label>
                                                <div class="input-group custom-input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Late Fee') }}"
                                                        value="{{ $tenant->late_fee_type == TYPE_FIXED ? $tenant->late_fee : $tenant->general_rent + $tenant->general_rent * $tenant->late_fee * 0.01 }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-4 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Incident Receipt') }}</label>
                                                <div class="input-group custom-input-group">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Incident Receipt') }}"
                                                        value="{{ $tenant->incident_receipt }}" disabled>
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-6 col-lg-4 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Payment due on date') }}</label>
                                                <div class="custom-datepicker">
                                                    <div class="custom-datepicker-inner position-relative">
                                                        <input type="text" class="datepicker form-control"
                                                            autocomplete="off" placeholder="dd-mm-yy"
                                                            value="{{ $tenant->due_date }}" disabled>
                                                        <i class="ri-calendar-2-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <style>
                                                input[type="hidden"] {
                                                    display: none;
                                                }
                                            </style>
                                            <div class="col-md-6 col-lg-4 mb-25">
                                                <label
                                                    class="label-text-title color-heading font-medium mb-2">{{ __('Keycode') }}</label>
                                                <div class="input-group">
                                                    <input id="show_start" type="text" value="*******"
                                                        class="form-control multiple-description" disabled>
                                                    <input id="show_after" type="hidden" 
                                                        value="{{ isset($tenant->keycode) ? decrypt($tenant->keycode) : '' }}"
                                                        class="form-control multiple-description"
                                                        placeholder="{{ __('Keycode') }}" disabled>
                    
                                                    @if (Auth::user()->role == USER_ROLE_OWNER)
                                                        <div class="input-group-append">
                                                            <button type="button" class="mt-3 toggle-keycode"
                                                                onclick="toggleKeycode(this)">
                                                                <i class="ri-eye-line"></i>
                                                            </button>
                                                        </div>
                                                    @elseif(Auth::user()->role == USER_ROLE_TENANT)
                                                        <button type="button" class="mt-3 toggle-keycode"
                                                            onclick="toggleKeycode(this)">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                    @endif
                    
                                                </div>
                    
                                            </div>
                                            <script>
                                               function toggleKeycode(button) {
                                                    const showStart = document.getElementById('show_start');
                                                    const showAfter = document.getElementById('show_after');
                                                    const icon = button.querySelector('i'); 
                    
                                                    
                                                    if (showStart.style.display !== 'none') {
                                                        showStart.style.display = 'none';
                                                        showAfter.type = 'text'; // Change hidden input to text
                                                        showAfter.style.display = 'block'; // Show the text input
                                                        showAfter.focus(); // Optionally focus on the input
                    
                                                        // Change the eye icon to 'eye-off'
                                                        icon.classList.remove('ri-eye-line');
                                                        icon.classList.add('ri-eye-off-line');
                                                    } else {
                                                        // Show the text input and hide the hidden input
                                                        showStart.style.display = 'block';
                                                        showAfter.type = 'hidden'; // Change text input back to hidden
                                                        showAfter.style.display = 'none'; // Hide the text input
                    
                                                        // Change the eye icon back to 'eye'
                                                        icon.classList.remove('ri-eye-off-line');
                                                        icon.classList.add('ri-eye-line');
                                                    }
                                                }
                    
                                            </script>
                    
                                        </div>
                                        <!-- Tenants Details Home Details Edit Rent Information End -->
                                    </div>
                                </div>
                                <!-- Account Settings Content Box End -->
                            </div>
                            <!-- Tenants Home Details End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection