@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .checkbox-label {
            display: flex; /* Use flexbox to align items horizontally */
            align-items: center; /* Align items vertically in the center */
        }
    </style>

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
                            <li class="breadcrumb-item active">@lang('admin.doctor.patient_report')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table border-0 custom-table attent-table datatable mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.question')</th>
                                        <th>@lang('admin.answer')</th>
                                        <th>@lang('admin.note')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($patient_report)
                                    @foreach($patient_report->patient_report as $report)
                                        <tr>
                                            <td class="month-table"><h5>{{$lang == 'en' ?  $report->medical_report->question_en  : $report->medical_report->question_ar }}</h5></td>
                                            <td>
                                                <label class="checkbox-label">
                                                    @if($report->answer_flag == 'Yes')
                                                <span class="present-table attent-status"><i class="feather-check"></i></span>
                                                &nbsp;&nbsp; @lang('admin.Yes')
                                                &nbsp;&nbsp;
                                                <span class="absent-table attent-status"><i class="feather-x"></i></span>
                                                    &nbsp;&nbsp;  @lang('admin.No')
                                                    @else
                                                        <span class="absent-table attent-status"><i class="feather-x"></i></span>
                                                        &nbsp;&nbsp; @lang('admin.Yes')
                                                        &nbsp;&nbsp;
                                                        <span class="present-table attent-status"><i class="feather-check"></i></span>
                                                        &nbsp;&nbsp;  @lang('admin.No')
                                                    @endif
                                                </label>
                                            </td>
                                            <td>
                                                @if($report->answer_flag == 'Yes')
                                                {{ $report->reason ?? null }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
