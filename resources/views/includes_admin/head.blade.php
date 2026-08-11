<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="icon" type="image/png" href="/media/logo/logo_header.png">
    <link rel="shortcut icon" type="image/png" href="/media/logo/logo_header.png">
    <title>{{ trans('admin.takafol_title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Bootstrap CSS -->

    @if(in_array(auth()->user()->app_type, [1, 2, 5, 6, 7]))

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" type="text/css" href="/reception/assets/css/bootstrap.min.css">
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="/reception/assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="/reception/assets/plugins/fontawesome/css/all.min.css">

        <!-- Select2 CSS -->
        <link rel="stylesheet" type="text/css" href="/reception/assets/css/select2.min.css">

        <!-- Datepicker CSS -->
        <link rel="stylesheet" href="/reception/assets/css/bootstrap-datetimepicker.min.css">

{{--        @if((Request::segment(2) == 'attachments') || (Request::segment(2) == 'appointments') )--}}

        <link rel="stylesheet" href="/reception/assets/plugins/datatables/datatables.min.css">
{{--        @endif--}}

        <!-- Feathericon CSS -->
        <link rel="stylesheet" href="/reception/assets/plugins/feather/feather.css">

        <link rel="stylesheet" type="text/css" href="/reception/assets/css/style.css">

    @else
    @if(app()->getLocale()=='en')

        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/all.min.css">

    <!-- Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">


    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap-datetimepicker.min.css">

    <!-- Datatables CSS -->
    <link rel="stylesheet" href="/assets/plugins/datatables/datatables.min.css">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="/assets/plugins/feather/feather.css">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">

    @else
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" type="text/css" href="/assets_rtl/css/bootstrap.rtl.min.css">

        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="/assets_rtl/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="/assets_rtl/plugins/fontawesome/css/all.min.css">

        <!-- Select2 CSS -->
        <link rel="stylesheet" type="text/css" href="/assets_rtl/css/select2.min.css">


        <link rel="stylesheet" href="/assets_rtl/css/bootstrap-datetimepicker.min.css">

        <!-- Datatables CSS -->
        <link rel="stylesheet" href="/assets_rtl/plugins/datatables/datatables.min.css">

        <!-- Feathericon CSS -->
        <link rel="stylesheet" href="/assets_rtl/plugins/feather/feather.css">

        <!-- Main CSS -->
        <link rel="stylesheet" type="text/css" href="/assets_rtl/css/style.css">

{{--        <link href="https://fonts.googleapis.com/css?family=Tajawal" rel='stylesheet'>--}}
        <style type="text/css">
            body, h1, h2, h3, h4, h5, h6, * {
                /*font-family: 'Tajawal', serif;*/
                font-weight: bold;
            }
        </style>
    @endif
    @endif



</head>
