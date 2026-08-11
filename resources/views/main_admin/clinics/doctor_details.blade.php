@extends('layout_new.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('clinic-details',$doctor->parent_id)}}">@lang('main.clinic_details') </a>
                            </li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('main.doctor_details.profile')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about-info">
                                        <h4>@lang('main.doctor_details.profile') <span><a href="javascript:;"><i
                                                        class="feather-more-vertical"></i></a></span></h4>
                                    </div>
                                    <div class="doctor-profile-head">
                                        <div class="profile-bg-img">
                                            <img src="{{ $doctor->image }}" alt="{{ $doctor->name }}"
                                                 style="width: 1084px;height: 318px">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4">
                                                <div class="profile-user-box">
                                                    <div class="profile-user-img">
                                                        <img src="{{ $doctor->image }}" alt="Profile">
                                                        <div class="form-group doctor-up-files profile-edit-icon mb-0">
                                                            <div class="uplod d-flex">
                                                                <label class="file-upload profile-upbtn mb-0">
                                                                    {{--                                                                    <img  src="/assets/img/icons/camera-icon.svg"  alt="Profile"></i><input type="file">--}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="names-profiles">
                                                        <h4>{{ $doctor->name }}</h4>
                                                        <h5>{{ app()->getLocale() == 'en' ? $doctor->degree->name_en ?? null : $doctor->degree->name_ar ?? null }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 d-flex align-items-center">
                                                <div class="follow-group">
                                                    <div class="doctor-follows">
                                                        <h5>@lang('main.doctor_details.points')</h5>
                                                        <h4>0</h4>
                                                    </div>
                                                    <div class="doctor-follows">
                                                        <h5>@lang('main.doctor_details.complain')</h5>
                                                        <h4>{{$doctor->complaints_count}}</h4>
                                                    </div>
                                                    <div class="doctor-follows">
                                                        <h5>@lang('main.doctor_details.reservations')</h5>
                                                        <h4>{{$doctor->reservations_count ?? 0}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--                                            <div class="col-lg-4 col-md-4 d-flex align-items-center">--}}
                                            {{--                                                <div class="follow-btn-group">--}}
                                            {{--                                                    <button type="submit" class="btn btn-info follow-btns">Follow</button>--}}
                                            {{--                                                    <button type="submit" class="btn btn-info message-btns">Message</button>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="doctor-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="heading-detail ">
                                            <h4 class="mb-3">@lang('main.doctor_details.About me')</h4>
                                            <p>{{ app()->getLocale() == 'en' ? $doctor->info : $doctor->info_ar }}.</p>
                                        </div>
                                        <div class="about-me-list">
                                            <ul class="list-space">
                                                <li>
                                                    <h4>@lang('main.doctor_details.gender')</h4>
                                                    <span>{{ $doctor->gender == 1 ? trans('admin.male') : trans('admin.female')}}</span>
                                                </li>
                                                <li>
                                                    <h4>@lang('main.doctor_details.reservations_done')</h4>
                                                    <span>{{$doctor->reservations_done_count}}</span>
                                                </li>
                                                <li>
                                                    <h4>@lang('main.doctor_details.reservations_cancel')</h4>
                                                    <span>{{$doctor->reservations_cancel_count}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="doctor-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="heading-detail">
                                            <h4>@lang('accounting.settings'):</h4>
                                        </div>
                                        <div class="skill-blk">
                                            <div class="skill-statistics">
                                                <div class="skills-head">
                                                    <h5>@lang('admin.appointments_online') ( % )</h5>
                                                    <p>{{ $doctor->condition->appointments_online ?? 0 }} %</p>
                                                </div>
                                                <div class="progress mb-0">
                                                    <div class="progress-bar bg-operations" role="progressbar"
                                                         style="width: {{ $doctor->condition->appointments_online ?? 0 }}%"
                                                         aria-valuenow="{{ $doctor->condition->appointments_online ?? 0 }}"
                                                         aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="skill-statistics">
                                                <div class="skills-head">
                                                    <h5>@lang('admin.appointments_reception')
                                                        ( % )</h5>
                                                    <p>{{ $doctor->condition->appointments_reception ?? 0 }}%</p>
                                                </div>
                                                <div class="progress mb-0">
                                                    <div class="progress-bar bg-statistics" role="progressbar"
                                                         style="width: {{ $doctor->condition->appointments_reception ?? 0 }}%"
                                                         aria-valuenow="{{ $doctor->condition->appointments_reception ?? 0 }}"
                                                         aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="skill-statistics">
                                                <div class="skills-head">
                                                    <h5>@lang('admin.number_patients') </h5>
                                                    <p>{{ $doctor->condition->number_patients ?? 0 }}%</p>
                                                </div>
                                                <div class="progress mb-0">
                                                    <div class="progress-bar bg-endoscopic" role="progressbar"
                                                         style="width: {{ $doctor->condition->number_patients ?? 0 }}%"
                                                         aria-valuenow="{{ $doctor->condition->number_patients ?? 0 }}"
                                                         aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="doctor-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="heading-detail">
                                            <h4>@lang('admin.specialization')</h4>
                                        </div>
                                        @foreach($doctor->specialties as $index=>$specialty)
                                            <div class="personal-activity">
                                                <div class="views-personal">
                                                    <h4>{{ $index + 1 }}
                                                        - {{$specialty->specialties->name_ar ?? null}}</h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="doctor-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="tab-content-set">
                                            <ul class="nav">
                                                <li>
                                                    <a href="{{ route('doctor-details',$doctor->id) }}"
                                                       class="active"><span class="set-about-icon me-2"><img
                                                                src="/assets/img/icons/menu-icon-02.svg"
                                                                alt=""></span>@lang('main.doctor_details.About me')</a>
                                                </li>
                                                {{--                                                <li>--}}
                                                {{--                                                    <a href="doctor-setting.html"><span class="set-about-icon me-2"><img src="/assets/img/icons/menu-icon-16.svg" alt=""></span>Settings</a>--}}
                                                {{--                                                </li>--}}
                                            </ul>
                                        </div>
                                        <div class="personal-list-out">
                                            <div class="row">
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="detail-personal">
                                                        <h2>@lang('admin.doctor_name')</h2>
                                                        <h3>{{ $doctor->name ?? null }}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="detail-personal">
                                                        <h2>@lang('admin.phone') </h2>
                                                        <h3>{{ $doctor->phone }}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="detail-personal">
                                                        <h2>@lang('admin.email')</h2>
                                                        <h3>{{$doctor->email}}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-md-6">
                                                    <div class="detail-personal">
                                                        <h2>@lang('admin.created_at')</h2>
                                                        <h3>{{ $doctor->created_at }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hello-park">
                                            <p>{{ app()->getLocale() == 'en' ? $doctor->info : $doctor->info_ar }}.</p>
                                        </div>

                                        @php
                                            $activeTab = true;
                                        @endphp

                                        <ul class="nav nav-tabs mb-3" id="reservationTabs" role="tablist">
                                            @foreach($groupedReservations as $statusId => $reservations)
                                                @php
                                                    $statusName = app()->getLocale() == 'en'
                                                        ? $reservations->first()->reservation_status->name_en
                                                        : $reservations->first()->reservation_status->name_ar;
                                                @endphp
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link @if($activeTab) active @endif"
                                                       id="tab-{{ $statusId }}"
                                                       data-bs-toggle="tab"
                                                       href="#content-{{ $statusId }}"
                                                       role="tab"
                                                       aria-controls="content-{{ $statusId }}"
                                                       aria-selected="{{ $activeTab ? 'true' : 'false' }}">
                                                        {{ $statusName }}
                                                    </a>
                                                </li>
                                                @php $activeTab = false; @endphp
                                            @endforeach
                                        </ul>

                                        <div class="tab-content" id="reservationTabsContent">
                                            @php $activeTab = true; @endphp
                                            @foreach($groupedReservations as $statusId => $reservations)
                                                <div class="tab-pane fade @if($activeTab) show active @endif"
                                                     id="content-{{ $statusId }}" role="tabpanel"
                                                     aria-labelledby="tab-{{ $statusId }}">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table border-0 custom-table comman-table datatable mb-0">
                                                            <thead>
                                                            <tr>
                                                                <th>@lang('admin.waiting_number')</th>
                                                                <th>@lang('admin.patient_name')</th>
                                                                <th>@lang('admin.date')</th>
                                                                <th>@lang('admin.phone')</th>
                                                                <th>@lang('admin.ID_Number')</th>
                                                                <th>@lang('admin.file_number')</th>
                                                                <th>@lang('admin.file_type')</th>
                                                                <th>@lang('admin.status')</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($reservations as $index => $waiting)
                                                                <tr>
                                                                    <td>
                                                                        @if($waiting->waiting_list == 1 && $waiting->status_id != 6)
                                                                            <div class="circle"
                                                                                 style="background-color: #24FE18;text-align: center">{{ $index + 1 }}</div>
                                                                        @else
                                                                            <div class="circle"
                                                                                 style="background-color: #FA8902;text-align: center">{{ $index + 1 }}</div>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('patient-file', $waiting->id) }}">
                                                                            <img
                                                                                src="{{ $waiting->user->image ?? null }}"
                                                                                width="28" height="28"
                                                                                class="rounded-circle m-r-5" alt="">
                                                                            {{ $waiting->user->name ?? null }}
                                                                        </a>
                                                                    </td>
                                                                    <td>{{ $waiting->date . ' ' . $waiting->appointment ?? null }}</td>
                                                                    <td>{{ $waiting->user->phone ?? null }}</td>
                                                                    <td>{{ $waiting->user->ID_Number ?? null }}</td>
                                                                    <td>{{ $waiting->user->file_number ?? null }}</td>
                                                                    <td>@lang('admin.cash')</td>
                                                                    <td>
                                                                        {{ app()->getLocale() == 'en' ? $waiting->reservation_status->name_en : $waiting->reservation_status->name_ar }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @php $activeTab = false; @endphp
                                            @endforeach
                                        </div>


                                        {{--                                        <div class="hello-park mb-2">--}}
                                        {{--                                            <h5>@lang('admin.Appointments')</h5>--}}
                                        {{--                                            <div class="table-responsive">--}}
                                        {{--                                                <table class="table border-0 custom-table comman-table datatable mb-0">--}}
                                        {{--                                                    <thead>--}}
                                        {{--                                                    <tr>--}}
                                        {{--                                                    <th>@lang('admin.waiting_number')</th>--}}
                                        {{--                                                    <th>@lang('admin.patient_name')</th>--}}
                                        {{--                                                    <th>@lang('admin.date')</th>--}}
                                        {{--                                                    <th>@lang('admin.phone')</th>--}}
                                        {{--                                                    <th>@lang('admin.ID_Number')</th>--}}
                                        {{--                                                    <th>@lang('admin.file_number')</th>--}}
                                        {{--                                                    <th>@lang('admin.file_type')</th>--}}
                                        {{--                                                    <th>@lang('admin.status')</th>--}}
                                        {{--                                                    </tr>--}}
                                        {{--                                                    </thead>--}}
                                        {{--                                                    <tbody>--}}
                                        {{--                                                    @foreach($doctor->reservations as $index=>$waiting)--}}
                                        {{--                                                        <tr>--}}
                                        {{--                                                            <td>--}}
                                        {{--                                                                @if($waiting->waiting_list == 1 && $waiting->status_id != 6)--}}
                                        {{--                                                                    <div class="circle" style="background-color: #24FE18;text-align: center">{{ $index + 1 }}</div>--}}
                                        {{--                                                                @else--}}
                                        {{--                                                                    <div class="circle" style="background-color: #FA8902;text-align: center">{{ $index + 1 }}</div>--}}
                                        {{--                                                                @endif--}}
                                        {{--                                                            </td>--}}
                                        {{--                                                            <td ><a--}}
                                        {{--                                                                    href="{{ route('patient-file', $waiting->id) }}"><img--}}
                                        {{--                                                                        width="28" height="28"--}}
                                        {{--                                                                        src="{{$waiting->user->image ?? null}}"--}}
                                        {{--                                                                        class="rounded-circle m-r-5"--}}
                                        {{--                                                                        alt="">{{$waiting->user->name ?? null}}--}}
                                        {{--                                                                </a></td>--}}
                                        {{--                                                            <td>{{ $waiting->date .' ' . $waiting->appointment ?? null }}</td>--}}
                                        {{--                                                            <td>{{ $waiting->user->phone ?? null }}</td>--}}
                                        {{--                                                            <td>{{ $waiting->user->ID_Number ?? null }}</td>--}}
                                        {{--                                                            <td>{{ $waiting->user->file_number ?? null }}</td>--}}
                                        {{--                                                            <td>@lang('admin.cash')</td>--}}
                                        {{--                                                            <td>--}}
                                        {{--                                                                {{  app()->getLocale() == 'en' ? $waiting->reservation_status->name_en : $waiting->reservation_status->name_ar }}--}}
                                        {{--                                                            </td>--}}

                                        {{--                                                        </tr>--}}
                                        {{--                                                    @endforeach--}}
                                        {{--                                                    </tbody>--}}
                                        {{--                                                </table>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}

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
