    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{URL::asset('media/logo/logo_header.png')}}">
    <link rel="shortcut icon" type="image/png" href="{{URL::asset('media/logo/logo_header.png')}}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{URL::asset('media/logo/logo_header.png')}}">

@if (!Route::is(['layout-mini', 'layout-hidden', 'layout-hover-view', 'layout-full-width', 'layout-rtl', 'login-basic', 'login-illustration', 'login-cover', 'login', 'register-basic', 'register-illustration', 'register-cover', 'forgot-password-basic', 'forgot-password-illustration', 'forgot-password-cover', 'reset-password-basic', 'reset-password-illustration', 'reset-password-cover', 'email-verification-basic', 'email-verification-illustration', 'email-verification-cover', 'success-basic', 'success-illustration', 'success-cover', 'two-step-verification-basic', 'two-step-verification-illustration', 'two-step-verification-cover', 'lock-screen', 'error-404', 'error-500', 'coming-soon', 'under-maintenance']))
    <!-- Theme Config Js -->
    @if(app()->getLocale()=='en')
    <script src="{{URL::asset('build/js/theme-script.js')}}"></script>
@endif
@endif

    @if(app()->getLocale()=='en')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/css/bootstrap.min.css')}}">
@endif

    @if(app()->getLocale()=='ar')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/css/bootstrap.rtl.min.css')}}">
@endif

    <!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{URL::asset('build/plugins/fontawesome/css/fontawesome.min.css')}}">
	<link rel="stylesheet" href="{{URL::asset('build/plugins/fontawesome/css/all.min.css')}}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/tabler-icons/tabler-icons.min.css')}}">

@if (Route::is(['icon-bootstrap']))
    <!-- Bootstrap Icon CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/bootstrap/bootstrap-icons.min.css')}}">
@endif

@if (Route::is(['icon-feather', 'tables-basic']))
    <!-- Feather CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/feather/feather.css')}}">
@endif

@if (Route::is(['icon-flag']))
    <!-- Flag CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/flags/flags.css')}}">
@endif

@if (Route::is(['icon-ionic']))
    <!-- Ionic CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/ionic/ionicons.css')}}">
@endif

    <!-- Material CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/material/materialdesignicons.css')}}">

@if (Route::is(['icon-pe7']))
    <!-- Pe7 CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/pe7/pe-icon-7.css')}}">
@endif

@if (Route::is(['icon-remix']))
    <!-- Remix Icon CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/remix/remixicon.css')}}">
@endif

@if (Route::is(['icon-simpleline']))
    <!-- Simpleline CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/simpleline/simple-line-icons.css')}}">
@endif

@if (Route::is(['icon-themify']))
    <!-- Themify CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/themify/themify.css')}}">
@endif

@if (Route::is(['icon-typicons']))
    <!-- Typicon CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/typicons/typicons.css')}}">
@endif

@if (Route::is(['icon-weather']))
    <!-- Weather CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/icons/weather/weathericons.css')}}">
@endif

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/simplebar/simplebar.min.css')}}">

    <!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="{{URL::asset('build/plugins/daterangepicker/daterangepicker.css')}}">
    @if(app()->getLocale()=='ar')
    <!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="{{URL::asset('build/css/bootstrap-datetimepicker.min.css')}}">
    @endif

    <!-- Bootstrap Tagsinput CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">


@if (Route::is(['contact-messages', 'create-patient', 'edit-patient', 'new-appointment', 'patient-details', 'patients-doctor-details', 'security-settings', 'ticket-details', 'tickets']))
	<!-- intltelinput CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/intltelinput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{URL::asset('build/plugins/intltelinput/css/demo.css')}}">
@endif

@if (Route::is(['assets', 'expenses', 'income', 'invoices', 'patient-details', 'patient-doctor-details', 'payments', 'roles-and-permissions', 'services', 'staffs', 'transactions', 'ui-rangeslider']))
    <!-- Rangeslider CSS -->
	<link rel="stylesheet" href="{{URL::asset('build/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
	<link rel="stylesheet" href="{{URL::asset('build/plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">
@endif

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/css/dataTables.bootstrap5.min.css')}}">

@if (Route::is(['notificationsList.create']))
    <!-- Quill CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/quill/quill.snow.css')}}">
@endif

@if (Route::is(['form-editors']))
    <!-- Quill css -->
    <link href="{{URL::asset('build/plugins/quill/quill.core.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('build/plugins/quill/quill.snow.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('build/plugins/quill/quill.bubble.css')}}" rel="stylesheet" type="text/css">
@endif

@if (Route::is(['email-reply', 'search-list', 'social-feed']))
    <!-- Fancybox -->
	<link rel="stylesheet" href="{{URL::asset('build/plugins/fancybox/jquery.fancybox.min.css')}}">
@endif

@if (Route::is(['kanban-view']))
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{URL::asset('build/css/owl.carousel.min.css')}}">
@endif

@if (Route::is(['chart-c3']))
    <!-- ChartC3 CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/c3-chart/c3.min.css')}}">
@endif

@if (Route::is(['chart-morris']))
    <!-- Morris CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/morris/morris.css')}}">
@endif

@if (Route::is(['form-pickers', 'kanban-view', 'notes', 'todo-list', 'todo']))
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/flatpickr/flatpickr.min.css')}}">
@endif

@if (Route::is(['form-range-slider']))
    <!-- nouisliderribute css -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/nouislider/nouislider.min.css')}}">
@endif

{{--@if (Route::is(['notificationsList.create']))--}}
    <!-- Choices CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/choices.js/public/assets/styles/choices.min.css')}}">
{{--@endif--}}

@if (Route::is(['form-wizard']))
    <!-- Wizard CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/twitter-bootstrap-wizard/form-wizard.css')}}">
@endif

@if (Route::is(['gallery', 'ui-lightbox', 'widgets']))
    <!-- Glightbox CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/lightbox/glightbox.min.css')}}">
@endif

@if (Route::is(['maps-leaflet']))
    <!-- Leaflet Maps CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/leaflet/leaflet.css')}}">
@endif

@if (Route::is(['maps-vector']))
    <!-- Jsvector Maps -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/jsvectormap/css/jsvectormap.min.css')}}">
@endif

@if (Route::is(['ui-sweetalerts']) || Route::is('cms.*'))
    <!-- Sweetalert2 CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/sweetalert2/sweetalert2.min.css')}}">
@endif


    <!-- Toatr CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/toastr/toatr.css')}}">


    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/plugins/select2/css/select2.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{URL::asset('build/css/style.css')}}" id="app-style">

    <!-- Modern Arabic + Latin Font (Tajawal) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --tj-font-ar: 'Tajawal', 'Cairo', 'Segoe UI', system-ui, sans-serif;
            --tj-header-bg: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            --tj-header-shadow: 0 2px 12px rgba(46, 55, 164, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
            --tj-primary: #2E37A4;
            --tj-accent: #0E9384;
        }

        html, body,
        h1, h2, h3, h4, h5, h6,
        .navbar-header, .navbar-header *,
        .sidebar, .sidebar *,
        .dropdown-menu, .dropdown-menu *,
        button, input, select, textarea, .btn,
        .card, .card *, .table, .table * {
            font-family: var(--tj-font-ar) !important;
        }

        .ti,
        [class^="ti-"],
        [class*=" ti-"],
        .sidebar .ti,
        .navbar-header .ti {
            font-family: "tabler-icons" !important;
            font-style: normal;
            font-weight: normal;
        }

        .fa,
        .fa-classic,
        .fa-solid,
        .fas {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900;
        }

        .fa-regular,
        .far {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 400;
        }

        .fa-brands,
        .fab {
            font-family: "Font Awesome 6 Brands" !important;
        }

        .mdi {
            font-family: "Material Design Icons" !important;
        }

        body {
            font-weight: 400;
            letter-spacing: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6,
        .fw-bold, .fw-semibold, .fw-medium {
            letter-spacing: -0.01em;
        }

        .ms-auto {
            margin-top: auto;
        }

        /* ============================
           Header polish
           ============================ */
        .navbar-header {
            background: var(--tj-header-bg) !important;
            box-shadow: var(--tj-header-shadow);
            border-bottom: 1px solid rgba(46, 55, 164, 0.08) !important;
            min-height: 64px !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .navbar-header::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 3px;
            background: linear-gradient(90deg, var(--tj-primary) 0%, var(--tj-accent) 100%);
            opacity: 0.9;
        }

        .navbar-header .topbar-menu {
            min-height: 64px;
            align-items: center;
        }

        .navbar-header .topbar-menu .header-item {
            height: 64px;
        }

        .navbar-header .topbar-menu .header-item .topbar-link {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: 1px solid rgba(46, 55, 164, 0.1);
            background: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
            color: #344054;
            transition: all 0.2s ease;
        }

        .navbar-header .topbar-menu .header-item .topbar-link:hover {
            background: linear-gradient(135deg, rgba(46, 55, 164, 0.08), rgba(14, 147, 132, 0.08));
            color: var(--tj-primary);
            border-color: rgba(46, 55, 164, 0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(46, 55, 164, 0.12);
        }

        .navbar-header .header-search .form-control {
            height: 38px !important;
            font-size: 13px !important;
            border-radius: 12px !important;
            border: 1px solid rgba(46, 55, 164, 0.12) !important;
            background: #fff !important;
            padding-inline-start: 38px;
            padding-inline-end: 42px;
            min-width: 260px;
            transition: all 0.2s ease;
        }

        .navbar-header .header-search .form-control:focus {
            border-color: rgba(46, 55, 164, 0.45) !important;
            box-shadow: 0 0 0 4px rgba(46, 55, 164, 0.08) !important;
        }

        .navbar-header .header-search .form-control::placeholder {
            color: #98A2B3 !important;
            font-weight: 400;
        }

        .navbar-header .header-search .header-search-icon {
            background: #F2F4F7;
            border: 1px solid #EAECF0;
            color: #667085 !important;
            font-size: 12px !important;
        }

        .navbar-header .logo img {
            max-height: 36px;
            width: auto;
            object-fit: contain;
        }

        /* Language switch button */
        .btn-liner-gradient {
            border-radius: 10px !important;
            padding: 8px 14px !important;
            font-weight: 600;
            font-size: 13px;
            box-shadow: 0 4px 10px rgba(46, 55, 164, 0.18);
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.02em;
        }

        .btn-liner-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(46, 55, 164, 0.25);
        }

        /* Profile dropdown trigger */
        .navbar-header .profile-dropdown > a {
            padding: 3px;
            border-radius: 50rem;
            border: 1px solid rgba(46, 55, 164, 0.12);
            background: #fff;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.06);
            transition: all 0.2s ease;
        }

        .navbar-header .profile-dropdown > a:hover {
            border-color: rgba(46, 55, 164, 0.35);
            box-shadow: 0 4px 10px rgba(46, 55, 164, 0.15);
        }

        .navbar-header .profile-dropdown img {
            border-radius: 50rem;
            object-fit: cover;
        }

        /* Notification badge dot */
        .notification-badge {
            box-shadow: 0 0 0 2px #fff;
        }

        /* Dropdown menus */
        .navbar-header .dropdown-menu {
            border: 1px solid rgba(46, 55, 164, 0.08);
            box-shadow: 0 10px 30px rgba(16, 24, 40, 0.1);
            border-radius: 14px;
            padding: 8px;
        }

        .navbar-header .dropdown-menu .dropdown-item {
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 13.5px;
        }

        .navbar-header .dropdown-menu .dropdown-item:hover {
            background: rgba(46, 55, 164, 0.06);
        }

        /* RTL refinements */
        [dir="rtl"] .navbar-header .header-search .form-control {
            text-align: right;
        }

        [dir="rtl"] .navbar-header .header-search .header-search-icon {
            right: auto;
            left: 5px;
        }

        @media (max-width: 991.98px) {
            .navbar-header { min-height: 58px !important; }
            .navbar-header .topbar-menu,
            .navbar-header .topbar-menu .header-item { height: 58px; }
        }
    </style>
