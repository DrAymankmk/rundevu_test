@extends('includes_admin.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.setting')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="settings-menu-links">
                <ul class="nav nav-tabs menu-tabs p-0 gap-2 bg-transparent">
                    @foreach($all_settings as $setting_item)
                        @php
                            $isActive =  Request::segment(3) == $setting_item->settings_type && Request::segment(4) == $setting_item->app_type ?? 0 ;
                        @endphp
                    <li class="nav-item {{ $isActive ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('app-setting',[$setting_item->settings_type,$setting_item->app_type ?? 0]) }}">{{ trans('main.'.$setting_item->settings_type)}} ({{ app()->getLocale() == 'en' ? $setting_item->type->name_en ?? trans('admin.employees') : $setting_item->type->name_ar ?? trans('admin.employees') }})</a>
                    </li>
                    @endforeach

                </ul>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ trans('main.'.$setting->settings_type ?? null)}} ({{ app()->getLocale() == 'en' ? $setting->type->name_en ?? null : $setting->type->name_ar ?? null }})</h5>
                        </div>
                        <div class="card-body pt-0">
                            <form action="{{ route('update-setting', $setting->id ?? null) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="settings-form">

                                    <div class="input-block">
                                        <p class="settings-label">@lang('admin.image') <span class="star-red">*</span></p>
                                        <div class="settings-btn">
                                            <input type="file" accept="image/*" name="image" id="file"  class="hide-input">
                                            <label for="file" class="upload">
                                                <i class="feather-upload"></i>
                                            </label>
                                        </div>
                                        <div class="upload-images">
                                            <img src="{{$setting->image ?? null}}" alt="Image">
                                            <a href="javascript:void(0);" class="btn-icon logo-hide-btn">
{{--                                                <i class="feather-x-circle"></i>--}}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="input-block form-placeholder">
                                        <label>@lang('main.title_en')<span class="star-red">*</span></label>
                                          <br>
                                        <input type="text" name="title_en" class="form-control" value="{{$setting->title_en ?? null}}" required>
                                    </div>
                                    <br>
                                    <div class="input-block form-placeholder">
                                        <label>@lang('main.title_ar')<span class="star-red">*</span></label>
                                          <br>
                                        <input type="text" name="title_ar" class="form-control" value="{{$setting->title_ar ?? null}}" required>
                                    </div>
                                    <br>
                                    <div class="input-block">
                                        <label>@lang('main.content_en') <span class="star-red">*</span></label>
                                          <br>
                                        <textarea class="form-control" name="content_en" rows="10" cols="15" required>{{ $setting->content_en ?? null }}</textarea>
                                    </div>
                                    <br>

                                    <div class="input-block">
                                        <label>@lang('main.content_ar') <span class="star-red">*</span></label>
                                          <br>
                                        <textarea class="form-control" name="content_ar" rows="10" cols="15" required>{{ $setting->content_ar ?? null }}</textarea>
                                    </div>
                                    <br>
                                    <div class="input-block mb-0">
                                        <div class="settings-btns">
                                            <button type="submit" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.edit')</button>
                                            <button type="submit" class="btn btn-secondary btn-rounded">@lang('admin.cancel')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
