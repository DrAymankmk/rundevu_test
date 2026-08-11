<!DOCTYPE html>
@if(app()->getLocale()=='ar')
    <html lang="ar" dir="rtl">
    @else
        <html lang="en" dir="ltr">
        @endif
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <meta name="csrf-token" content="{{csrf_token()}}">
            <title>{{ trans('admin.takafol_title') }}</title>
            <link href="{{ asset('media/logo/logo_header.png')}}" sizes="128x128" rel="icon" type="image/png"/>
            <link href="{{ asset('media/logo/logo_header.png')}}" sizes="128x128" rel="shortcut icon" type="image/png"/>
            <!-- Google font-->
            <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900"
                  rel="stylesheet">
            <link
                href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
                rel="stylesheet">
            {{--    <link--}}
            {{--        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css"--}}
            {{--        rel="stylesheet">--}}
            <link
                href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
                rel="stylesheet">
            <!-- Font Awesome-->
            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/fontawesome.css')}}">
            <!-- ico-font-->
            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/icofont.css')}}">
{{--            <script src="{{asset('admin/js/tiktok-font.js')}}" crossorigin="anonymous"></script>--}}
            <!-- Themify icon-->
            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/themify.css')}}">
            <!-- Flag icon-->
            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/flag-icon.css')}}">
            <!-- Feather icon-->
            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/feather-icon.css')}}">

            <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/select2.css') }}">

            <!-- Plugins css start-->
{{--            <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/timepicker.css')}}">--}}
            @if( Request::segment(2) == 'employee-shifts')
            <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/date-picker.css')}}">
            @endif

            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/sweetalert2.css')}}">
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/rating.css') }}">

{{--            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/date-picker.css')}}">--}}

            {{--    <!-- Plugins css start-->--}}
            <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/datatables.css')}}">


            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/prism.css')}}">
            <!-- Plugins css Ends-->
            <!-- Bootstrap css-->
            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/bootstrap.css')}}">
            <!-- App css-->
            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/style.css')}}">

            <link id="color" rel="stylesheet" href="{{asset('/admin/css/light-1.css')}}" media="screen">
            <!-- Responsive css-->
            <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/responsive.css')}}">
            <link href="https://fonts.googleapis.com/css?family=Tajawal" rel='stylesheet'>
            <style type="text/css">
                #map {
                    width: 100%;
                    height: 50%;
                }

                body, h1, h2, h3, h4, h5, h6, * {
                    font-family: 'Tajawal', serif;
                }
            </style>



        </head>



