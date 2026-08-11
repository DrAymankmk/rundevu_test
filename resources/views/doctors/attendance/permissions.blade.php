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
                            <li class="breadcrumb-item active">@lang('admin.view_employee_permissions')</li>
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
                                            <h3>@lang('admin.view_employee_permissions')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                               placeholder="@lang('admin.search_here')">
                                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg"
                                                                            alt=""></a>
                                                    </form>
                                                </div>
                                                <div class="add-group">
                                                    <a href="javascript:;"
                                                       class="btn btn-primary doctor-refresh ms-2"><img
                                                            src="/assets/img/icons/re-fresh.svg" alt=""></a>
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

                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                    <tr>
                                        <th style="display: none">#</th>
                                        <th>@lang('admin.date')</th>
                                        <th>@lang('admin.permission_type')</th>
                                        <th>@lang('admin.reason')</th>
                                        <th>@lang('admin.permission_status')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['permissions'] as $index=>$permission)
                                        <tr>
                                            <td style="display: none">{{ $index + 1 }}</td>
                                            <td>{{ $permission->dateA }}</td>
                                            <td>{{ app()->getLocale() == 'en' ? $permission->permissions_type->name_en : $permission->permissions_type->name_ar }}</td>
                                            <td>{{ $permission->reason }}</td>
                                            @if($permission->status == 0)
                                                <td style="color: brown">@lang('admin.pending')</td>
                                            @elseif($permission->status == 1)
                                                <td style="color: #00c292">@lang('admin.acceptable')</td>
                                            @else
                                                <td style="color: #ff0000"> @lang('admin.unacceptable') </td>
                                            @endif

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $data['permissions']->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
