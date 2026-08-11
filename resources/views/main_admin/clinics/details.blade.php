<?php $page = 'patient-details'; ?>
@extends('layout_new.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- page header start -->
            <div class="mb-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center"> <a href="{{route('clinics')}}" class="text-dark"> <i class="ti ti-chevron-left me-1"></i>{{ trans('main.clinics') }}</a></h6>
            </div>
            <!-- page header end -->

            <!-- card start -->
            <div class="card">
                <div class="row align-items-end">
                    <div class="col-xl-9 col-lg-8">
                        <div class="d-sm-flex align-items-center position-relative z-0 overflow-hidden p-3">
{{--                            <img src="{{$clinic->qr_code}}" alt="img" class="z-n1 position-absolute end-0 top-0 d-none d-lg-flex">--}}
                            <a href="javascript:void(0);" class="avatar avatar-xxxl patient-avatar me-2 flex-shrink-0">
                                <img src="{{$clinic->image}}" alt="product" class="rounded">
                            </a>
                            <div>
                                <p class="text-primary mb-1">{{ $clinic->ID_Number ?? null }}</p>
                                <h5 class="mb-1"><a href="javascript:void(0);" class="fw-bold">{{ $clinic->name ?? null }}</a></h5>
                                <p class="mb-3">{{ $clinic->address ?? null }}</p>
                                <div class="d-flex align-items-center flex-wrap">
                                    <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-phone me-1 text-dark"></i>@lang('main.Phone') : <span class="text-dark ms-1">{{ $clinic->phone }}</span></p>
                                    <span class="mx-2 text-light">|</span>
                                    <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-calendar-time me-1 text-dark"></i>{{ trans('admin.Created Date') }}: <span class="text-dark ms-1">{{ $clinic->created_at->format('d M Y') }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="p-3 text-lg-end">
                            <div class="mb-4">
                                <a href="tel:{{$clinic->phone}}" class="btn btn-outline-white shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14 me-2"><i class="ti ti-phone"></i></a>
                                <a href="mailto:{{$clinic->email}}" class="btn btn-outline-white shadow-sm rounded-circle d-inline-flex align-items-center p-2 fs-14 me-2"><i class="ti ti-mail"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- card end -->

            <!-- row start -->
            <div class="row">
                <div class="col-xl-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0"><i class="ti ti-user-star me-1"></i>@lang('main.about_clinic')</h5>
                        </div>
                        <div class="card-body pb-0">
                            @php
                                $detailsContractModel = optional($clinic->contract)->contract_model ?? 'cash_commission';
                            @endphp
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-package fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.package_type')</h6>
                                            <p class="mb-0">{{ app()->getLocale() == 'en' ? $clinic->currentPackage?->name_en : $clinic->currentPackage?->name_ar }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-file-dollar fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.contract_model')</h6>
                                            <p class="mb-0">
                                                @if($detailsContractModel === 'annual_subscription')
                                                    @lang('main.contract_annual_subscription_short')
                                                @elseif($detailsContractModel === 'online_payment_commission')
                                                    @lang('main.contract_online_payment_commission_short')
                                                @else
                                                    @lang('main.contract_cash_commission_short')
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-calendar-time fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.expired_date')</h6>
                                            <p class="mb-0">{{ $clinic->package_end_date ?? null }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-calendar-event fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.contract_number')</h6>
                                            <p class="mb-0">{{ $clinic->communication_officer_phone ?? null }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-droplet fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.communication_officer')</h6>
                                            <p class="mb-0">{{ $clinic->communication_officer ?? null }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-gender-male fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.responsible_person_name')</h6>
                                            <p class="mb-0">{{ $clinic->monitor_username ?? null }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-mail fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.Email Address')</h6>
                                            <p class="mb-0 text-break">{{ $clinic->email ?? null }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-placeholder fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.city')</h6>
                                            <p class="mb-0 text-break">{{ $clinic->city->name_ar ?? null }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-link fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">@lang('main.website')</h6>
                                            <p class="mb-0 text-break">{{ $clinic->website ?? null }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i class="ti ti-share fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Social Media</h6>
                                            <p class="mb-0 text-break">
                                                @foreach([
                                                    'Facebook' => $clinic->facebook_url,
                                                    'Instagram' => $clinic->instagram_url,
                                                    'TikTok' => $clinic->tiktok_url,
                                                    'Snapchat' => $clinic->snapchat_url,
                                                    'YouTube' => $clinic->youtube_url,
                                                ] as $label => $url)
                                                    @if($url)
                                                        <a href="{{ $url }}" target="_blank" class="me-3">{{ $label }}</a>
                                                    @endif
                                                @endforeach
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->

            @php
                $contract = $clinic->contract ?: new \App\Models\ClinicContract(\App\Models\ClinicContract::defaultAttributes());
                $contractModel = old('contract_model', $contract->contract_model);
            @endphp
            <div class="row">
                <div class="col-xl-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0"><i class="ti ti-file-dollar me-1"></i>@lang('main.contract_booking_system')</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('update-clinic', $clinic->id) }}" method="POST">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-lg-4">
                                        <label class="form-label">@lang('main.contract_model')</label>
                                        <select name="contract_model" id="contract_model" class="form-control">
                                            <option value="cash_commission" {{ $contractModel === 'cash_commission' ? 'selected' : '' }}>@lang('main.contract_cash_commission')</option>
                                            <option value="online_payment_commission" {{ $contractModel === 'online_payment_commission' ? 'selected' : '' }}>@lang('main.contract_online_payment_commission')</option>
                                            <option value="annual_subscription" {{ $contractModel === 'annual_subscription' ? 'selected' : '' }}>@lang('main.contract_annual_subscription')</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 contract-commission-field">
                                        <label class="form-label">@lang('main.platform_commission_rate')</label>
                                        <input type="number" name="commission_rate" class="form-control" min="0" max="100" step="0.01" value="{{ old('commission_rate', $contract->commission_rate) }}">
                                    </div>
                                    <div class="col-lg-2 contract-subscription-field">
                                        <label class="form-label">@lang('main.annual_subscription_amount')</label>
                                        <input type="number" name="annual_subscription_amount" class="form-control" min="0" step="0.01" value="{{ old('annual_subscription_amount', $contract->annual_subscription_amount) }}">
                                    </div>
                                    <div class="col-lg-2 contract-subscription-field">
                                        <label class="form-label">@lang('main.annual_subscription_starts_at')</label>
                                        <input type="date" name="annual_subscription_starts_at" class="form-control" value="{{ old('annual_subscription_starts_at', optional($contract->annual_subscription_starts_at)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-lg-2 contract-subscription-field">
                                        <label class="form-label">@lang('main.annual_subscription_ends_at')</label>
                                        <input type="date" name="annual_subscription_ends_at" class="form-control" value="{{ old('annual_subscription_ends_at', optional($contract->annual_subscription_ends_at)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-lg-9 contract-subscription-field">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="rendezvous_badge_enabled" value="1" id="rendezvous_badge_enabled" {{ old('rendezvous_badge_enabled', $contract->rendezvous_badge_enabled) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="rendezvous_badge_enabled">@lang('main.rendezvous_badge_enabled')</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 text-lg-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ti ti-device-floppy me-1"></i>@lang('main.save_contract')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- tab start -->
            <ul class="nav nav-tabs nav-bordered mb-3" id="appTypeTabs">
                <li class="nav-item">
                    <a href="#appointments" data-bs-toggle="tab" aria-expanded="false" class="nav-link active bg-transparent">
                        <span>@lang('main.Appointments')</span>
                    </a>
                </li>
                @foreach($app_types as $index=>$app_type)
                <li class="nav-item">
                    <a href="javascript:void(0);"  class="nav-link bg-transparent"
                       data-id="{{ $app_type->id }}" data-clinic = {{$clinic->id}} data-bs-toggle="tab" aria-expanded="true" >
                        <span>{{ app()->getLocale() == 'en' ? $app_type->name_en : $app_type->name_ar }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            <!-- tab end -->

            <!-- tab content start -->
            <div class="tab-content">
                <div class="tab-pane show active" id="appointments">
                    <!--  Start Filter -->
                    <div class=" d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <div class="search-set mb-3">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <div class="table-search d-flex align-items-center mb-0">
                                        <div class="search-input">
                                            <a href="javascript:void(0);" class="btn-searchset"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex right-content align-items-center flex-wrap mb-3">
                                <div class="reportrange-picker d-flex align-items-center reportrange">
                                    <i class="ti ti-calendar text-gray-5 fs-14 me-1"></i><span class="reportrange-picker-field">16 Apr 25 - 16 Apr 25</span>
                                </div>
                            </div>
                        </div>

{{--                        <div class="d-flex table-dropdown mb-3 right-content align-items-center flex-wrap row-gap-3">--}}
{{--                            <div class="dropdown me-2">--}}
{{--                                <a href="javascript:void(0);" class="bg-white border rounded btn btn-md text-dark fs-14 py-1 align-items-center d-flex fw-normal" data-bs-toggle="dropdown" data-bs-auto-close="outside">--}}
{{--                                    <i class="ti ti-filter text-gray-5 me-1"></i>@lang('admin.Filters')--}}
{{--                                </a>--}}
{{--                                <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown p-0">--}}
{{--                                    <div class="d-flex align-items-center justify-content-between border-bottom filter-header">--}}
{{--                                        <h4 class="mb-0 fw-bold">@lang('admin.Filter')</h4>--}}
{{--                                        <div class="d-flex align-items-center">--}}
{{--                                            <a href="javascript:void(0);" class="link-danger text-decoration-underline">@lang('admin.Clear All')</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <form action="#">--}}
{{--                                        <div class="filter-body pb-0">--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <div class="d-flex align-items-center justify-content-between">--}}
{{--                                                    <label class="form-label">Doctor</label>--}}
{{--                                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>--}}
{{--                                                </div>--}}
{{--                                                <div class="dropdown">--}}
{{--                                                    <a href="javascript:void(0);" class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">--}}
{{--                                                        Select <i class="ti ti-chevron-down ms-auto"></i>--}}
{{--                                                    </a>--}}
{{--                                                    <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">--}}
{{--                                                        <div class="mb-3">--}}
{{--                                                            <div class="input-icon-start input-icon position-relative">--}}
{{--                                                                <span class="input-icon-addon fs-12">--}}
{{--                                                                    <i class="ti ti-search"></i>--}}
{{--                                                                </span>--}}
{{--                                                                <input type="text" class="form-control form-control-md" placeholder="Search">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <ul class="mb-3">--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    <span class="avatar avatar-xs rounded-circle me-2"><img src="{{URL::asset('build/img/doctors/doctor-01.jpg')}}" class="flex-shrink-0 rounded-circle" alt="img"></span>Dr. Mick Thompson--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    <span class="avatar avatar-xs rounded-circle me-2"><img src="{{URL::asset('build/img/doctors/doctor-02.jpg')}}" class="flex-shrink-0 rounded-circle" alt="img"></span>Dr. Sarah Johnson--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    <span class="avatar avatar-xs rounded-circle me-2"><img src="{{URL::asset('build/img/doctors/doctor-03.jpg')}}" class="flex-shrink-0 rounded-circle" alt="img"></span>Dr. Emily Carter--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    <span class="avatar avatar-xs rounded-circle me-2"><img src="{{URL::asset('build/img/doctors/doctor-04.jpg')}}" class="flex-shrink-0 rounded-circle" alt="img"></span>Dr. David Lee--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-0">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    <span class="avatar avatar-xs rounded-circle me-2"><img src="{{URL::asset('build/img/doctors/doctor-05.jpg')}}" class="flex-shrink-0 rounded-circle" alt="img"></span>Dr. Anna Kim--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                        </ul>--}}
{{--                                                        <div class="row g-2">--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <a href="javascript:void(0);" class="btn btn-outline-white w-100 close-filter">Cancel</a>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <a href="javascript:void(0);" class="btn btn-primary w-100">Select</a>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <div class="d-flex align-items-center justify-content-between">--}}
{{--                                                    <label class="form-label">Designation</label>--}}
{{--                                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>--}}
{{--                                                </div>--}}
{{--                                                <div class="dropdown">--}}
{{--                                                    <a href="javascript:void(0);" class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">--}}
{{--                                                        Select <i class="ti ti-chevron-down ms-auto"></i>--}}
{{--                                                    </a>--}}
{{--                                                    <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">--}}
{{--                                                        <div class="mb-3">--}}
{{--                                                            <div class="input-icon-start input-icon position-relative">--}}
{{--                                                                <span class="input-icon-addon fs-12">--}}
{{--                                                                    <i class="ti ti-search"></i>--}}
{{--                                                                </span>--}}
{{--                                                                <input type="text" class="form-control form-control-md" placeholder="Search">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <ul class="mb-3">--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Cardiologist--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Orthopedic Surgeon--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Pediatrician--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Gynecologist--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Psychiatrist--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Neurosurgeon--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Oncologist--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Pulmonologist--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Urologist--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Dermatologist--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                        </ul>--}}
{{--                                                        <div class="row g-2">--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <a href="javascript:void(0);" class="btn btn-outline-white w-100 close-filter">Cancel</a>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <a href="javascript:void(0);" class="btn btn-primary w-100">Select</a>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <div class="d-flex align-items-center justify-content-between">--}}
{{--                                                    <label class="form-label">Mode</label>--}}
{{--                                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>--}}
{{--                                                </div>--}}
{{--                                                <div class="dropdown">--}}
{{--                                                    <a href="javascript:void(0);" class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">--}}
{{--                                                        Select <i class="ti ti-chevron-down ms-auto"></i>--}}
{{--                                                    </a>--}}
{{--                                                    <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">--}}
{{--                                                        <div class="mb-3">--}}
{{--                                                            <div class="input-icon-start input-icon position-relative">--}}
{{--                                                                <span class="input-icon-addon fs-12">--}}
{{--                                                                    <i class="ti ti-search"></i>--}}
{{--                                                                </span>--}}
{{--                                                                <input type="text" class="form-control form-control-md" placeholder="Search">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <ul class="mb-3">--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    In Person--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-0">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Online--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                        </ul>--}}
{{--                                                        <div class="row g-2">--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <a href="javascript:void(0);" class="btn btn-outline-white w-100 close-filter">Cancel</a>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <a href="javascript:void(0);" class="btn btn-primary w-100">Select</a>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label class="form-label mb-1 text-dark fs-14 fw-medium">Date<span class="text-danger">*</span></label>--}}
{{--                                                <div class="input-icon-end position-relative">--}}
{{--                                                    <input type="text" class="form-control bookingrange" placeholder="dd/mm/yyyy">--}}
{{--                                                    <span class="input-icon-addon">--}}
{{--                                                        <i class="ti ti-calendar"></i>--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <div class="d-flex align-items-center justify-content-between">--}}
{{--                                                    <label class="form-label">Status</label>--}}
{{--                                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>--}}
{{--                                                </div>--}}
{{--                                                <div class="dropdown">--}}
{{--                                                    <a href="javascript:void(0);" class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">--}}
{{--                                                        Select <i class="ti ti-chevron-down ms-auto"></i>--}}
{{--                                                    </a>--}}
{{--                                                    <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">--}}
{{--                                                        <div class="mb-3">--}}
{{--                                                            <div class="input-icon-start input-icon position-relative">--}}
{{--                                                                <span class="input-icon-addon fs-12">--}}
{{--                                                                    <i class="ti ti-search"></i>--}}
{{--                                                                </span>--}}
{{--                                                                <input type="text" class="form-control form-control-md" placeholder="Search">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <ul class="mb-3">--}}
{{--                                                            <li class="mb-1">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Checked Out--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-0">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Checked In--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-0">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Cancelled--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-0">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Schedule--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                            <li class="mb-0">--}}
{{--                                                                <label class="dropdown-item px-2 d-flex align-items-center text-dark">--}}
{{--                                                                    <input class="form-check-input m-0 me-2" type="checkbox">--}}
{{--                                                                    Confirmed--}}
{{--                                                                </label>--}}
{{--                                                            </li>--}}
{{--                                                        </ul>--}}
{{--                                                        <div class="row g-2">--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <a href="javascript:void(0);" class="btn btn-outline-white w-100 close-filter">Cancel</a>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="col-6">--}}
{{--                                                                <a href="javascript:void(0);" class="btn btn-primary w-100">Select</a>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="filter-footer d-flex align-items-center justify-content-end border-top">--}}
{{--                                            <a href="javascript:void(0);" class="btn btn-light btn-md me-2 fw-medium close-filter">Close</a>--}}
{{--                                            <button type="submit" class="btn btn-primary btn-md fw-medium">Filter</button>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                    <!--  End Filter -->

                    <!--  Start Table -->
                    <div class="table-responsive">
                        <table class="table datatable table-nowrap">
                            <thead class="">
                            <tr>
                                <th class="no-sort">
                                    @lang('main.Date & Time')
                                </th>
                                <th>@lang('main.User Name')</th>
                                <th>@lang('main.Doctor Name')</th>
                                <th>@lang('admin.ID_Number')</th>
                                <th>@lang('admin.file_number')</th>
                                <th>@lang('admin.status')</th>
{{--                                <th>@lang('admin.action')</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clinic->reservations_clinics as $reservation)
                            <tr>
                                <td>{{ $reservation->date }} - {{ $reservation->appointment }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{url('doctor-details')}}" class="avatar me-2 flex-shrink-0">
                                            <img src="{{ $reservation->user->image ?? null }}" alt="img" class="rounded-circle">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 mb-1 text-truncate"><a href="{{ route('doctor-details',$reservation->doctor_id) }}" class="fw-semibold">{{ $reservation->user->name }}</a></h6>
                                            <p class="mb-0 fs-13 text-truncate">@if($reservation->user->gender == 1) @lang('admin.male')@else @lang('admin.female') @endif , {{ $reservation->user->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{url('doctor-details')}}" class="avatar me-2 flex-shrink-0">
                                            <img src="{{ $reservation->doctor->image }}" alt="img" class="rounded-circle">
                                        </a>
                                        <div>
                                            <h6 class="fs-14 mb-1 text-truncate"><a href="{{url('doctor-details')}}" class="fw-semibold">{{ $reservation->doctor->name }}</a></h6>
                                            <p class="mb-0 fs-13 text-truncate">{{ $reservation->doctor->phone ?? null }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $reservation->user->ID_Number ?? null }}</td>
                                <td>{{ $reservation->user->file_number ?? null }}</td>
                                @php
                                    if ($reservation->status_id == 1) {
                                        $color = "badge-soft-warning rounded text-warning";
                                    } elseif ($reservation->status_id == 2) {
                                        $color = "badge-soft-success rounded text-success";
                                    } elseif ($reservation->status_id == 6) {
                                        $color = "badge-soft-info rounded text-info";
                                    } else {
                                        $color = "badge-soft-danger rounded text-danger";
                                    }
                                @endphp

                                <td> <span class="badge fs-13 {{$color ?? null}} fw-medium">{{ app()->getLocale() == 'en' ? $reservation->reservation_status->name_en : $reservation->reservation_status->name_ar }}</span> </td>


{{--                                <td class="action-item">--}}
{{--                                    <a href="javascript:void(0);" data-bs-toggle="dropdown">--}}
{{--                                        <i class="ti ti-dots-vertical"></i>--}}
{{--                                    </a>--}}
{{--                                    <ul class="dropdown-menu p-2">--}}
{{--                                        <li>--}}
{{--                                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="offcanvas" data-bs-target="#view_details">View</a>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#delete_modal">Delete</a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </td>--}}
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--  End Table -->

                </div>
                    <div class="tab-pane fade show active" id="dynamicTabContent">
                        <p class="text-muted">@lang('main.loading')</p>
                </div>
            </div>
            <!-- tab content end -->
        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent
    </div>

    <!-- ========================
        End Page Content
    ========================= -->


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const model = document.getElementById('contract_model');
            const subscriptionFields = document.querySelectorAll('.contract-subscription-field');
            const commissionFields = document.querySelectorAll('.contract-commission-field');

            function toggleContractFields() {
                if (!model) {
                    return;
                }

                const isSubscription = model.value === 'annual_subscription';
                subscriptionFields.forEach(field => field.style.display = isSubscription ? '' : 'none');
                commissionFields.forEach(field => field.style.display = isSubscription ? 'none' : '');
            }

            if (model) {
                model.addEventListener('change', toggleContractFields);
                toggleContractFields();
            }

            function loadTabContent(id,clinic) {
                const contentBox = document.getElementById('dynamicTabContent');
                contentBox.innerHTML = `<div class="text-center my-4">{{ trans('main.loading') }}</div>`;

                fetch(`/admin/load-tab-content/${id}/${clinic}`)
                    .then(res => res.text())
                    .then(html => {
                        contentBox.innerHTML = html;
                    })
                    .catch(() => {
                        contentBox.innerHTML = `<div class="text-danger">حدث خطأ أثناء تحميل المحتوى</div>`;
                    });
            }

            // تحميل أول تبويب افتراضياً
            const firstTab = document.querySelector('#appTypeTabs a[data-id]');
            if (firstTab) {
                loadTabContent(firstTab.getAttribute('data-id'), firstTab.getAttribute('data-clinic'));
            }

            // عند النقر على تبويب
            document.querySelectorAll('#appTypeTabs a[data-id]').forEach(tab => {
                tab.addEventListener('click', function () {
                    document.querySelectorAll('#appTypeTabs a').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const id = this.getAttribute('data-id');
                    const clinic = this.getAttribute('data-clinic');
                    loadTabContent(id,clinic);
                });
            });
        });
    </script>


@endsection
