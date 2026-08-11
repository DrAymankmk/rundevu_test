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
                            <li class="breadcrumb-item active">@lang('admin.doctor.prescription record')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.doctor.prescription record')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                               placeholder="@lang('admin.search_here')">
                                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg" alt=""></a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-04.svg" alt=""></a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <div class="staff-search-table">
                                <form action="{{ route('prescription-record') }}" method="get">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label >@lang('admin.From') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_from">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label >@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_to">
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-xl-4 ms-auto">
                                            <div class="doctor-submit">
                                                <button type="submit" class="btn btn-primary submit-list-form me-2">@lang('admin.Search')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                    <tr>
                                        <th style="display: none">#</th>
                                        <th>@lang('admin.name')</th>
                                        <th>@lang('admin.phone')</th>
                                        <th>@lang('admin.ID_Number')</th>
                                        <th>@lang('admin.date')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['prescription_record'] as $index=>$record)
                                        <tr>
                                            <td style="display: none">{{ $index + 1 }}</td>
                                            <td class="profile-image"><a href="{{ route($route, $record->reservation_id) }}"><img
                                                        width="28" height="28"
                                                        src="{{$record->users->image ?? null}}"
                                                        class="rounded-circle m-r-5"
                                                        alt="">{{$record->users->name ?? null}}
                                                </a></td>
                                            <td>{{ $record->users->phone ?? null }}</td>
                                            <td>{{ $record->users->ID_Number ?? null }}</td>

                                            <td>{{ $record->created_at }}</td>

                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                       data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">

                                                        <a class="dropdown-item"
                                                           href="{{ route($route, $record->reservation_id) }}"><i class="feather-eye m-r-5"></i> @lang('admin.doctor.prescription record')
                                                        </a>
                                                    </div>
                                                </div>


                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $data['prescription_record']->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
