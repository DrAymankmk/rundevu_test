@if(auth()->user()->app_type == 2)
    <div class="header no-print d-flex align-items-center justify-content-between px-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{route('admin.dashboard')}}" class="logo">
                <img src="/media/logo/logo_header.png" width="42" height="42" alt="">
                <span>{{ trans('admin.takafol_title') }}</span>
            </a>

            <a id="toggle_btn" class="p-0" href="javascript:void(0);"><img src="/assets/img/icons/bar-icon.svg"  alt=""></a>
            <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar"><img src="/assets/img/icons/bar-icon.svg"  alt=""></a>
        </div>
        <ul class="nav user-menu gap-2">
            <li class="nav-item m-0 desktop-language-switch">
                @if(\Illuminate\Support\Facades\Session::get('lang') == 'en' || app()->getLocale() == 'en')
                    <a onclick="changeLang()" href="{{URL::to('changeLanguageAdmin','ar')}}" class="hasnotifications nav-link" aria-label="Change language to Arabic">
                        <i class="fa-solid fa-globe"></i>
                        <img class="switchLang" src="/assets/img/ar.png" alt="" width="30" height="30">
                        <span>AR</span>
                    </a>
{{--                    <a href="" onclick="changeLang()" class="nav-link"><img class="switchLang" width="30" height="30" src="/assets/img/ar.png" alt=""></a>--}}
                @else
                    <a onclick="changeLang()" href="{{URL::to('changeLanguageAdmin','en')}}" class="hasnotifications nav-link" aria-label="Change language to English">
                        <i class="fa-solid fa-globe"></i>
                        <img class="switchLang" src="/assets/img/en.jpg" alt="" width="30" height="30">
                        <span>EN</span>
                    </a>
{{--                    <a href="" onclick="changeLang()" class="nav-link"><img class="switchLang" width="30" height="30" src="/assets/img/en.jpg" alt=""></a>--}}
                @endif
            </li>
            <li class="nav-item dropdown d-none d-sm-block m-0">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><img src="/assets/img/icons/note-icon-02.svg" alt=""><span class="pulse"></span> </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <span>@lang('admin.Notifications')</span>
                    </div>
                    <div class="drop-scroll">
                        <ul class="notification-list">
{{--                            <li class="notification-message">--}}
{{--                                <a href="activities.html">--}}
{{--                                    <div class="media">--}}
{{--											<span class="avatar">--}}
{{--												<img alt="John Doe" src="assets/img/user.jpg" class="img-fluid">--}}
{{--											</span>--}}
{{--                                        <div class="media-body">--}}
{{--                                            <p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>--}}
{{--                                            <p class="noti-time"><span class="notification-time">4 mins ago</span></p>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                        </ul>
                    </div>
{{--                    <div class="topnav-dropdown-footer">--}}
{{--                        <a href="activities.html">View all Notifications</a>--}}
{{--                    </div>--}}
                </div>
            </li>
            <li class="nav-item dropdown d-none d-sm-block m-0">
                <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link"><img src="/assets/img/icons/note-icon-01.svg" alt=""><span class="pulse"></span> </a>
            </li>
            <li class="nav-item dropdown has-arrow user-profile-list m-0">
                <a href="#" class="dropdown-toggle nav-link user-link gap-2" data-bs-toggle="dropdown">
                    <div class="user-names p-0">
                        <h5>{{ auth()->user()->name ?? null }} </h5>
                             @if(auth()->user()->app_type == 3)
                                <span>@lang('admin.doctor_account')</span>
                            @else
                                <span>{{ \App\Models\Clinic::app_type_account(auth()->user()->app_type) }}</span>
                            @endif

                    </div>
                    <span class="user-img">
							<img  src="{{ auth()->user()->image ?? null }}"  alt="..">
						</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('profile') }}">@lang('admin.doctor.personal_information')</a>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}">@lang('admin.Sign out')</a>
                </div>
            </li>
        </ul>
        <div class="mobile-language-switch d-sm-none d-flex">
            @if(\Illuminate\Support\Facades\Session::get('lang') == 'en' || app()->getLocale() == 'en')
                <a onclick="changeLang()"  href="{{URL::to('changeLanguageAdmin','ar')}}" class="hasnotifications nav-link" aria-label="Change language to Arabic">
                    <i class="fa-solid fa-globe"></i>
                    <img src="/assets/img/ar.png" class="switchLang2" alt="">
                    <span>AR</span>
                </a>
            @else
                <a onclick="changeLang()"  href="{{URL::to('changeLanguageAdmin','en')}}" class="hasnotifications nav-link" aria-label="Change language to English">
                    <i class="fa-solid fa-globe"></i>
                    <img src="/assets/img/en.jpg" class="switchLang2" alt="">
                    <span>EN</span>
                </a>
            @endif
        </div>
        <div class="dropdown mobile-user-menu float-end">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ route('profile') }}">@lang('admin.doctor.personal_information')</a>
                <a class="dropdown-item" href="{{ route('admin.logout') }}">@lang('admin.Sign out')</a>
            </div>
        </div>
    </div>
@else
    <style>
        .clean-admin-header {
            gap: 16px;
            min-height: 66px;
            border-bottom: 1px solid #eef1f7;
        }

        .clean-admin-header .logo {
            margin: 0;
            min-width: 170px;
        }

        .clean-admin-header .header-main-actions {
            gap: 12px;
            margin-inline-start: auto;
        }

        .clean-admin-header #toggle_btn,
        .clean-admin-header #mobile_btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eef3ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 38px;
        }

        .clean-admin-header .user-menu {
            margin: 0;
            gap: 10px;
            align-items: center;
        }

        .clean-admin-header .user-link {
            gap: 10px;
            padding: 4px 8px;
            border-radius: 12px;
        }

        @media (min-width: 992px) {
            .clean-admin-header #mobile_btn {
                display: none !important;
            }
        }

        @media (max-width: 991.98px) {
            .clean-admin-header #toggle_btn {
                display: none !important;
            }
        }
    </style>
    <div class="header no-print clean-admin-header d-flex align-items-center justify-content-between px-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{route('admin.dashboard')}}" class="logo">
                <img src="/media/logo/logo_header.png" width="42" height="42" alt="">
                <span>{{ trans('admin.takafol_title') }}</span>
            </a>
        </div>
        <div class="header-main-actions d-flex align-items-center">
        <a id="toggle_btn" href="javascript:void(0);"><img src="/assets/img/icons/bar-icon.svg" alt=""></a>
        <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar"><img src="/assets/img/icons/bar-icon.svg" alt=""></a>
        <ul class="nav user-menu float-end">
            {{--        <li class="nav-item dropdown d-none d-sm-block">--}}
            {{--            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><img--}}
            {{--                    src="/assets/img/icons/note-icon-02.svg" alt=""><span class="pulse"></span> </a>--}}
            {{--            <div class="dropdown-menu notifications">--}}
            {{--                <div class="topnav-dropdown-header">--}}
            {{--                    <span>@lang('admin.doctor.Notifications')</span>--}}
            {{--                </div>--}}
            {{--                <div class="drop-scroll">--}}
            {{--                    <ul class="notification-list">--}}
            {{--                        <li class="notification-message">--}}
            {{--                            <a href="{{route('notifications')}}">--}}
            {{--                                <div class="media">--}}
            {{--											<span class="avatar">--}}
            {{--												<img alt="John Doe" src="/assets/img/user.jpg" class="img-fluid">--}}
            {{--											</span>--}}
            {{--                                    <div class="media-body">--}}
            {{--                                        <p class="noti-details"><span class="noti-title">John Doe</span> added new task--}}
            {{--                                            <span class="noti-title">Patient appointment booking</span></p>--}}
            {{--                                        <p class="noti-time"><span class="notification-time">4 mins ago</span></p>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </li>--}}
            {{--                        <li class="notification-message">--}}
            {{--                            <a href="{{route('notifications')}}">--}}
            {{--                                <div class="media">--}}
            {{--                                    <span class="avatar">V</span>--}}
            {{--                                    <div class="media-body">--}}
            {{--                                        <p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed--}}
            {{--                                            the task name <span class="noti-title">Appointment booking with payment gateway</span>--}}
            {{--                                        </p>--}}
            {{--                                        <p class="noti-time"><span class="notification-time">6 mins ago</span></p>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </li>--}}
            {{--                        <li class="notification-message">--}}
            {{--                            <a href="{{route('notifications')}}">--}}
            {{--                                <div class="media">--}}
            {{--                                    <span class="avatar">L</span>--}}
            {{--                                    <div class="media-body">--}}
            {{--                                        <p class="noti-details"><span class="noti-title">Misty Tison</span> added <span--}}
            {{--                                                class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span>--}}
            {{--                                            to project <span class="noti-title">Doctor available module</span></p>--}}
            {{--                                        <p class="noti-time"><span class="notification-time">8 mins ago</span></p>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </li>--}}
            {{--                        <li class="notification-message">--}}
            {{--                            <a href="activities.html">--}}
            {{--                                <div class="media">--}}
            {{--                                    <span class="avatar">G</span>--}}
            {{--                                    <div class="media-body">--}}
            {{--                                        <p class="noti-details"><span class="noti-title">Rolland Webber</span> completed--}}
            {{--                                            task <span class="noti-title">Patient and Doctor video conferencing</span>--}}
            {{--                                        </p>--}}
            {{--                                        <p class="noti-time"><span class="notification-time">12 mins ago</span></p>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </li>--}}
            {{--                        <li class="notification-message">--}}
            {{--                            <a href="activities.html">--}}
            {{--                                <div class="media">--}}
            {{--                                    <span class="avatar">V</span>--}}
            {{--                                    <div class="media-body">--}}
            {{--                                        <p class="noti-details"><span class="noti-title">Bernardo Galaviz</span> added--}}
            {{--                                            new task <span class="noti-title">Private chat module</span></p>--}}
            {{--                                        <p class="noti-time"><span class="notification-time">2 days ago</span></p>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </a>--}}
            {{--                        </li>--}}
            {{--                    </ul>--}}
            {{--                </div>--}}
            {{--                --}}{{--                <div class="topnav-dropdown-footer">--}}
            {{--                --}}{{--                    <a href="activities.html">View all Notifications</a>--}}
            {{--                --}}{{--                </div>--}}
            {{--            </div>--}}
            {{--        </li>--}}
            {{--        <li class="nav-item dropdown d-none d-sm-block">--}}
            {{--            <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link"><img--}}
            {{--                    src="/assets/img/icons/note-icon-01.svg" alt=""><span class="pulse"></span> </a>--}}
            {{--        </li>--}}
            <li class="nav-item dropdown has-arrow user-profile-list">
                <a href="#" class="dropdown-toggle nav-link user-link" data-bs-toggle="dropdown">
                    <div class="user-names">
                        <h5>{{ auth()->user()->name ?? null }} </h5>
                        @if(auth()->user()->app_type == 3)
                            <span>@lang('admin.doctor_account')</span>
                        @else
                            <span>{{ \App\Models\Clinic::app_type_account(auth()->user()->app_type) }}</span>
                        @endif
                    </div>
                    <span class="user-img">
                    <img src="{{ auth()->user()->image ?? null }}" alt="Admin">
						</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('profile') }}">@lang('admin.doctor.personal_information')</a>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}">@lang('admin.Sign out')</a>
                </div>
            </li>
            <li class="nav-item desktop-language-switch">

                @if(\Illuminate\Support\Facades\Session::get('lang') == 'en' || app()->getLocale() == 'en')
                    <a href="{{URL::to('changeLanguageAdmin','ar')}}" class="hasnotifications nav-link" aria-label="Change language to Arabic">
                        <i class="fa-solid fa-globe"></i>
                        <img src="/assets/img/ar.png" alt="">
                        <span>AR</span>
                    </a>
                @else
                    <a href="{{URL::to('changeLanguageAdmin','en')}}" class="hasnotifications nav-link" aria-label="Change language to English">
                        <i class="fa-solid fa-globe"></i>
                        <img src="/assets/img/en.jpg" alt="">
                        <span>EN</span>
                    </a>
                @endif
            </li>
        </ul>
        <div class="mobile-language-switch d-sm-none d-flex">
            @if(\Illuminate\Support\Facades\Session::get('lang') == 'en' || app()->getLocale() == 'en')
                <a href="{{URL::to('changeLanguageAdmin','ar')}}" class="hasnotifications nav-link" aria-label="Change language to Arabic">
                    <i class="fa-solid fa-globe"></i>
                    <img src="/assets/img/ar.png" alt="">
                    <span>AR</span>
                </a>
            @else
                <a href="{{URL::to('changeLanguageAdmin','en')}}" class="hasnotifications nav-link" aria-label="Change language to English">
                    <i class="fa-solid fa-globe"></i>
                    <img src="/assets/img/en.jpg" alt="">
                    <span>EN</span>
                </a>
            @endif
        </div>
        <div class="dropdown mobile-user-menu float-end">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                    class="fa-solid fa-ellipsis-vertical"></i></a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ route('profile') }}">@lang('admin.doctor.personal_information')</a>
                <a class="dropdown-item" href="{{ route('admin.logout') }}">@lang('admin.Sign out')</a>
            </div>
        </div>
        </div>
    </div>
    @endif

