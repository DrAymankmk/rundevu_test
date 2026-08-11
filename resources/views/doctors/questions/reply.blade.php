@extends('includes_admin.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="row">
                <div class="col-sm-7 col-6">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                        <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                        <li class="breadcrumb-item active">@lang('admin.reply')</li>
                    </ul>
                </div>

            </div>
            <div class="card-box profile-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#"><img class="avatar" src="{{$reply->users->image ?? null}}" alt=""></a>
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0">{{$reply->users->name ?? null}}</h3>
                                            <div class="staff-id">ID : {{$reply->users->ID_Number ?? null}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <li>
                                                <span class="title">@lang('admin.phone'):</span>
                                                <span class="text"><a
                                                        href="tel:{{$reply->users->phone ?? null}}">{{$reply->users->phone ?? null}}</a></span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.email'):</span>
                                                <span class="text"><a
                                                        href="mailto:{{$reply->users->email ?? null}}">{{$reply->users->email ?? null}}</a></span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.dob'):</span>
                                                <span class="text">{{$reply->users->dob ?? null}}</span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.address'):</span>
                                                <span class="text">{{$reply->users->address ?? null}}</span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.gender'):</span>
                                                <span class="text"> @if ($reply->users->gender == 2)
                                                        @lang('admin.female')
                                                    @else
                                                        @lang('admin.male')
                                                    @endif</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-tabs">

                <div class="tab-content">
                    <div class="tab-pane show active" id="about-cont">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">@lang('admin.reply')</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <form class="needs-validation"
                                              action="{{route('add-reply',$reply->id)}}"
                                              method="POST" enctype="multipart/form-data">
                                            {{ method_field('POST') }}
                                            {{ csrf_field() }}
                                            <div class="settings-form">

                                                <div class="form-group">
                                                    <label>@lang('admin.question')</label>
                                                    <textarea class="form-control"
                                                              readonly>{{ $reply->complain }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label>@lang('admin.answer') <span class="star-red">*</span></label>
                                                    <textarea class="form-control"
                                                              name="message">{{ $reply->reply ?? null }}</textarea>
                                                </div>

                                                @if(empty($reply->reply))
                                                <div class="form-group mb-0">
                                                    <div class="settings-btns">
                                                        <button type="submit"
                                                                class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.save')</button>
                                                        <button type="submit"
                                                                class="btn btn-secondary btn-rounded">@lang('admin.cancel')</button>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
