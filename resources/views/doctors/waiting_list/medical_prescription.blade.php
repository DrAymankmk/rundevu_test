@extends('includes_admin.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.doctor.Medical prescription')</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="card-box profile-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="#"><img class="avatar" src="{{$reservation->clinic->image ?? null}}"
                                                     alt=""></a>

                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0 mb-0">{{$reservation->clinic->name ?? null}}</h3>
                                            <small class="text-muted">{{$reservation->clinic->phone ?? null}}</small>
                                            <div class="staff-id">@lang('admin.doctor_account')
                                                : {{$reservation->clinic->name ?? null}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">

                                            <li>
                                                <span class="title"><a href="#"><img class="avatar"
                                                                                     style="width: 90px;height: 90px"
                                                                                     src="{{$reservation->user->image ?? null}}"
                                                                                     alt=""></a></span>
                                                <span class="text"><a href="#"><img class="avatar"
                                                                                    style="width: 90px;height: 90px"
                                                                                    src="/media/reservations/{{$reservation->booking_number . '.png' ?? null}}"
                                                                                    alt=""></a></span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.name'):</span>
                                                <span class="text"><a
                                                        href="">{{$reservation->user->name ?? null}}</a></span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.gender'):</span>
                                                @if($reservation->user->gender == 1)
                                                    <span class="text"><a href="">@lang('admin.male')</a></span>
                                                @else
                                                    <span class="text"><a href="">@lang('admin.female')</a></span>
                                                @endif
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.phone'):</span>
                                                <span class="text"><a href="">{{$reservation->user->phone ?? null}}</a></span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.email'):</span>
                                                <span class="text"><a href="">{{$reservation->user->email ?? null}}</a></span>
                                            </li>
                                            <li>
                                                <span class="title">@lang('admin.ID_Number'):</span>
                                                <span class="text">{{$reservation->user->ID_Number ?? null}}</span>
                                            </li>

                                            <li>
                                                <span class="title">@lang('admin.dob'):</span>
                                                <span class="text">{{$reservation->user->dob ?? null}}</span>
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
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item"><a class="nav-link active" href="#about-cont"
                                            data-bs-toggle="tab">@lang('admin.diagnosis')</a></li>
                </ul>

                @if(count($reservation->reservation_drugs) > 0)
                <div class="tab-content">
                    <div class="tab-pane show active" id="about-cont">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">@lang('admin.add_diagnosis')</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                            <div class="settings-form">
                                                <div class="form-group">
                                                    <label for="diagnosis">@lang('admin.add_diagnosis')</label>
                                                    <textarea class="form-control" name="diagnosis" id="diagnosis"
                                                              placeholder="@lang('admin.add_diagnosis')">{{ $reservation->diagnosis ?? null }}</textarea>
                                                </div>


                                                <div class="card-box">
                                                    <h3 class="card-title">@lang('admin.doctor.Drug lists')</h3>
                                                    <div class="experience-box">
                                                        <ul class="experience-list">
                                                            @foreach($reservation->reservation_drugs as $section)
                                                            <li>
                                                                <div class="experience-user">
                                                                    <div class="before-circle"></div>
                                                                </div>
                                                                <div class="experience-content">
                                                                    <div class="timeline-content">
                                                                        @if($lang == 'en')
                                                                        <a href="#/" class="name">{{ $section->drugs->name_en }} {{ $section->drugs->concentration_ratio }} @lang('admin.doctor.'.$section->drugs->concentration_type)   @lang('admin.medicine_type.'.$section->drugs->medicine_type)</a>
                                                                        @else
                                                                            <a href="#/" class="name">{{ $section->drugs->name_ar }} {{ $section->drugs->concentration_ratio }} @lang('admin.doctor.'.$section->drugs->concentration_type)   @lang('admin.medicine_type.'.$section->drugs->medicine_type)</a>
                                                                        @endif
                                                                        <div>{{$section->repetition}} @lang('admin.time_per_day') - {{$section->nums_days}} @lang('admin.day')</div>
                                                                        <span class="time">{{$section->notes}}</span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            @endforeach

                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-0">
                                                    <div class="settings-btns">
                                                        <a href="{{route('patient-report', [$reservation->user_id,2])}}" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.medical_reports') &nbsp;&nbsp;
                                                            <i class="fa fa-check-square"></i></a>
                                                        <a href="{{route('add-medicine',$reservation->id)}}"
                                                           class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.add_medicine')</a>
                                                        <button type="submit"
                                                                class="border-0 btn btn-danger btn-gradient-primary btn-rounded">@lang('admin.quit_without_medication')</button>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @else
                    <div class="tab-content">
                        <div class="tab-pane show active" id="about-cont">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">@lang('admin.add_diagnosis')</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <form class="needs-validation"
                                                  action="{{route('reservation-notes',$reservation->id)}}"
                                                  method="POST">
                                                {{ method_field('POST') }}
                                                {{ csrf_field() }}
                                                <div class="settings-form">

                                                    <div class="form-group">
                                                        <label for="diagnosis">@lang('admin.add_diagnosis')</label>
                                                        <textarea class="form-control" name="diagnosis" id="diagnosis"
                                                                  placeholder="@lang('admin.add_diagnosis')">{{ $reservation->diagnosis ?? null }}</textarea>
                                                    </div>

                                                    <div class="form-group mb-0">
                                                        <div class="settings-btns">
                                                            <a href="" class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.medical_reports') &nbsp;&nbsp; <i class="fa fa-check-square"></i></a>
                                                            <a href="{{route('add-medicine',$reservation->id)}}"
                                                               class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.add_medicine')</a>
                                                            <button type="submit"
                                                                    class="border-0 btn btn-danger btn-gradient-primary btn-rounded">@lang('admin.quit_without_medication')</button>
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
                @endif

            </div>
        </div>

    </div>
@endsection
