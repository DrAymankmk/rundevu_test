@if(app()->getLocale() =='ar')
    <body main-theme-layout="rtl">
    @else
        <body>
        @endif<!-- Loader starts-->
        <div class="loader-wrapper">
            <div class="loader bg-white">
                <div class="whirly-loader"></div>
            </div>
        </div>
        <!-- Loader ends-->
        <!-- page-wrapper Start-->
        <div class="page-wrapper">

            <!-- Page Header Start-->
            <div class="page-main-header">
                <div class="main-header-right row">
                    <div class="main-header-left d-lg-none">
                        <div class="logo-wrapper"><a href=""><img src="{{ asset('media/logo/logo.png')}}" alt=""></a>
                        </div>
                    </div>
                    <div class="mobile-sidebar d-block">
                        <div class="media-body text-right switch-sm">
                            <label class="switch"><a><i id="sidebar-toggle" data-feather="align-left"></i></a></label>
                        </div>
                    </div>

                    <div class="nav-right col p-0">
                        <ul class="nav-menus">
                            <li>
                            </li>
                            <li><a class="text-dark" href="#" onclick="javascript:toggleFullScreen()"><i
                                        data-feather="maximize"></i></a></li>
                            <li class="onhover-dropdown"><a class="txt-dark" href="#">
                                    @if(app()->getLocale()  == \Illuminate\Support\Facades\Session::get('lang'))
                                        @if(\Illuminate\Support\Facades\Session::get('lang') == 'en')
                                            <i class="flag-icon flag-icon-us mr-2"></i>
                                        @else
                                            <i class="flag-icon flag-icon-sa mr-2"></i>
                                        @endif
                                        <span
                                            style="font-weight: bold">{{ strtoupper(\Illuminate\Support\Facades\Session::get('lang'))}}</span>
                                    @else
                                        <span style="font-weight: bold">EN</span>
                                    @endif
                                </a>
                                <ul class="language-dropdown onhover-show-div p-20">
                                    {{--                                        <li><a href="{{URL::to('changeLanguageAdmin',$lang->code)}}" data-lng="{{ $lang->code }}"><img src="{{$lang->image}}" style="width: 20px"/> {{$lang->name}}</a></li>--}}
                                    <li><a href="{{URL::to('changeLanguageAdmin','ar')}}" data-lng="ar">
                                            <i class="flag-icon flag-icon-sa mr-2"></i>
                                            AR</a></li>
                                    <br>
                                    <li><a href="{{URL::to('changeLanguageAdmin','en')}}" data-lng="ar">
                                            <i class="flag-icon flag-icon-us mr-2"></i>
                                            EN</a></li>

                                </ul>
                            </li>

                            <li><a href=""></a></li>
                            <li class="onhover-dropdown">
                                <div class="media align-items-center">
                                    <img class="align-self-center pull-right img-50 rounded-circle"
                                         src="{{ \Illuminate\Support\Facades\Auth::user()->image }}" alt="header-user">
                                    <div class="dotted-animation"><span class="animate-circle"></span><span
                                            class="main-circle"></span></div>
                                </div>
                                <ul class="profile-dropdown onhover-show-div p-20">
                                    <li><a href="{{ route('profile') }}" style="font-size: 12px;"><i
                                                data-feather="user"></i> @lang('admin.profile')</a></li>
                                    <li><a href="{{ route('admin.logout') }}" style="font-size: 12px;"><i
                                                data-feather="log-out"></i> @lang('admin.logout')</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
                    </div>


                </div>
            </div>
            <!-- Page Header Ends-->
            <!-- Page Body Start-->
            <div class="page-body-wrapper">
                <br>
                <!-- Page Sidebar Start-->
                <div class="page-sidebar">
                    <br>
                    <div class="main-header-left d-none d-lg-block">
                        <div class="logo-wrapper" style="text-align: center;padding-left: 30px"><a
                                href="{{route('admin.dashboard')}}">
                                <img src="{{ asset('media/logo/logo_185.png')}}"
                                     style="text-align: center;width: 55%;margin-top: 10px" alt=""></a></div>
                    </div>
                    <div class="sidebar custom-scrollbar">
                        <div class="sidebar-user text-center">
                            {{--                            <div><img class="img-60 rounded-circle" src="{{ Auth::user()->image }}"--}}
                            {{--                                      alt="#">--}}
                            {{--                                <div class="profile-edit"><a href="{{ route('profile') }}" target="_blank"><i--}}
                            {{--                                            data-feather="edit"></i></a></div>--}}
                            {{--                            </div>--}}
                            <h6 class="mt-3 f-14">{{ Auth::user()->name }}</h6>
                            <p>{{\App\Models\Clinic::app_type_account(\Illuminate\Support\Facades\Auth::user()->app_type) ?? null }}</p>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->app_type == 3)
                            <ul class="sidebar-menu">
                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}">
                                        <img src="{{ asset('media/icons/home_page.png') }}"/>&nbsp;
                                        &nbsp;<span>@lang('admin.dashboard')</span></a></li>

                                <li><a class="sidebar-header" href="{{ route('profile') }}">
                                        <img src="{{ asset('media/icons/personal_information.png') }}"/>&nbsp;
                                        <span> @lang('admin.doctor.personal_information')</span></a>
                                </li>


                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}">
                                        <img src="{{ asset('media/icons/doctor_appointments.png') }}"/>&nbsp;
                                        <span>@lang('admin.doctor.doctor_appointments')</span></a></li>


                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"> <img
                                            src="{{ asset('media/icons/View patient appointments.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.View patient appointments')</span></a>
                                </li>


                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"> <img
                                            src="{{ asset('media/icons/Medical_prescription_menu.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.Medical prescription')</span></a>
                                </li>


                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"> <img
                                            src="{{ asset('media/icons/prescription record.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.prescription record')</span></a>
                                </li>


                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"> <img
                                            src="{{ asset('media/icons/View patient appointments.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.View medical reports')</span></a>
                                </li>

                                <li><a class="sidebar-header" href="{{ route('drugs') }}"> <img
                                            src="{{ asset('media/icons/Medical_prescription_menu.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.Drug lists')</span></a>
                                </li>


                                <li><a class="sidebar-header" href="{{ route('attendance-departure') }}"> <img
                                            src="{{ asset('media/icons/Attendance and Departure.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.Attendance and Departure')</span></a>
                                </li>

                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"> <img
                                            src="{{ asset('media/icons/personal_information.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.Shifts')</span></a>
                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"> <img
                                            src="{{ asset('media/icons/Attendance and Departure.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.request a permission')</span></a>
                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"> <img
                                            src="{{ asset('media/icons/notifications.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.Notifications')</span></a>
                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"> <img
                                            src="{{ asset('media/icons/questions received menu.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.doctor.questions received')</span></a>
                                </li>


                                <li><a class="sidebar-header" href="{{ route('points-list') }}"> <img
                                            src="{{ asset('media/icons/my_points.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.My Points')</span></a>
                                </li>


                                <li><a class="sidebar-header" href="{{ route('change-password') }}"> <img
                                            src="{{ asset('media/icons/change_password.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.Change Password')</span></a>
                                </li>

                                <li><a class="sidebar-header" href="{{ route('setting','about') }}"> <img
                                            src="{{ asset('media/icons/abut_app.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.About The App')</span></a>
                                </li>
                                <li><a class="sidebar-header" href="{{ route('setting','privacy') }}"> <img
                                            src="{{ asset('media/icons/Privacy Policy.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.Privacy Policy')</span></a>
                                </li>
                                <li><a class="sidebar-header" href="{{ route('setting','terms') }}"> <img
                                            src="{{ asset('media/icons/Terms of use.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.Terms of use')</span></a>
                                </li>
                                <li><a class="sidebar-header" href="{{ route('admin.logout') }}"> <img
                                            src="{{ asset('media/icons/Sign out.png') }}"/>&nbsp;&nbsp;<span>@lang('admin.Sign out')</span></a>
                                </li>

                            </ul>
                        @else
                            <ul class="sidebar-menu">
                                <li><a class="sidebar-header" href="{{ route('admin.dashboard') }}"><i
                                            data-feather="home"></i>&nbsp;<span>@lang('admin.dashboard')</span></a></li>
                                @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('profile_view'))
                                    <li><a class="sidebar-header" href="{{ route('profile') }}"><i
                                                data-feather="database"></i>&nbsp;<span>@lang('admin.Manage facility data')</span></a>
                                    </li>
                                @endif

                                    <li><a class="sidebar-header" href="{{ route('admin.doctors.ratings') }}"><i data-feather="file-plus"></i>&nbsp;<span>@lang('admin.doctors_ratings')</span></a>
                                    </li>

                                @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('department_view'))

                                    <li><a class="sidebar-header" href="{{ route('departments') }}"><i
                                                data-feather="settings"></i>&nbsp;<span>@lang('admin.Add departments')</span></a>
                                    </li>
                                @endif
                                @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('offers_view'))

                                    <li><a class="sidebar-header" href="{{ route('offers') }}"><i
                                                data-feather="tag"></i>&nbsp;<span>@lang('admin.Manage Offers')</span></a>
                                    </li>
                                @endif
                                @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('specialties_view'))

                                    <li><a class="sidebar-header" href="{{ route('specialties') }}"><i
                                                data-feather="settings"></i>&nbsp;<span>@lang('admin.Manage available specialties')</span></a>
                                    </li>
                                @endif
                                @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('branches_view'))

                                    @if(\Illuminate\Support\Facades\Auth::user()->app_type != 7)
                                        <li><a class="sidebar-header" href="{{ route('branches') }}"><i
                                                    data-feather="settings"></i>&nbsp;<span>@lang('admin.Manage Branch')</span></a>
                                        </li>
                                    @endif
                                @endif
                                @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('supervisors_view'))

                                    <li><a class="sidebar-header" href="{{ route('supervisor') }}"><i
                                                data-feather="file"></i>&nbsp;<span>@lang('admin.SuperVisor Management')</span></a>
                                    </li>
                                @endif

                                @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('complaint_view'))

                                    <li><a class="sidebar-header" href="{{ route('contactUs') }}"><i
                                                data-feather="message-circle"></i>&nbsp;<span>@lang('admin.Complaints Box')</span></a>
                                    </li>
                                @endif
                                <li><a class="sidebar-header" href="{{ route('change-password') }}"><i
                                            data-feather="edit"></i>&nbsp;<span>@lang('admin.Change Password')</span></a>
                                </li>

                                <li><a class="sidebar-header" href="{{ route('setting','about') }}"><i
                                            data-feather="info"></i>&nbsp;<span>@lang('admin.About The App')</span></a>
                                </li>
                                <li><a class="sidebar-header" href="{{ route('setting','privacy') }}"><i
                                            data-feather="lock"></i>&nbsp;<span>@lang('admin.Privacy Policy')</span></a>
                                </li>
                                <li><a class="sidebar-header" href="{{ route('setting','terms') }}"><i
                                            data-feather="check-square"></i>&nbsp;<span>@lang('admin.Terms of use')</span></a>
                                </li>
                                <li><a class="sidebar-header" href="{{ route('admin.logout') }}"><i
                                            data-feather="log-out"></i>&nbsp;<span>@lang('admin.Sign out')</span></a>
                                </li>

                            </ul>
                        @endif
                    </div>
                </div>


                @yield('content')


                <!-- Page Sidebar Ends-->

                <!-- Right sidebar Ends-->
                <!-- footer start-->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 footer-copyright">
                                <p class="mb-0" style="font-weight: bold">@lang('admin.Copyright') @
                                    Randevou {{ date('Y') }}. @lang('admin.reserved')</p>
                            </div>
                            <div class="col-md-6">

                                <p class="pull-right mb-0">
                                    <a href="" target="_blank">
                                        <img src="{{ asset('media/logo/logo.png')}}" style="width:50px;"> </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
