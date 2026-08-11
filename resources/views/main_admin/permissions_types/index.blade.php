@extends('layout_new.mainlayout')
@section('content')
    <style>
        .modal-select label {
            z-index: 99999 !important;
        }

        .select2-container--default.select2-container--open {
            z-index: 9999 !important;
        }

        .add-table-invoice {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .success {
            background-color: #28C76F20;
        }

        .success i {
            color: #28C76F !important;
        }

        .danger {
            background-color: #ea545520;
        }

        .danger i {
            color: #ea5455 !important;
        }
    </style>

    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('main.packages')</a>
                            </li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active p-0">@lang('main.permissions_types')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Page Header End -->

            <!-- Table Data -->
            <div class="row">
                <!-- Branches -->
                <div class="col-sm-12">
                    <div class="card card-table show-entire">
                        <div class="card-body">
                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <form action="{{ route('permissionsTypes.index') }}" method="get">
                                    <div class="row align-items-center gap-2 d-md-flex d-block">
                                        <div class="col">
                                            <div class="doctor-table-blk">
                                                <h3>@lang('main.permissions_types') ({{ count($data['permissions_types']) }} )</h3>
                                                {{--                                                <div class="doctor-search-blk">--}}
                                                {{--                                                    <div class="top-nav-search table-search-blk">--}}
                                                {{--                                                        <input id="package-search" type="text"  class="form-control"--}}
                                                {{--                                                               placeholder="@lang('main.search_by_name')">--}}
                                                {{--                                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg" alt=""></a>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                    <div class="add-group">--}}
                                                {{--                                                        <a href="javascript:;" class="btn btn-primary doctor-refresh"><img src="/assets/img/icons/re-fresh.svg" alt=""></a>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                </div>--}}
                                            </div>
                                        </div>
                                        <div
                                            class="col-auto text-end py-2 ms-auto download-grp add-group sm:flex-row flex-col">
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                               data-bs-target="#add_permission"
                                               class="btn btn-primary text-nowrap w-100 border"><span>@lang('main.add_new_permission')</span></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /Table Header -->
                            <div class="position-relative">
                                <div class="table-loader">
                                    <div class="spinner"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('admin.name_ar')</th>
                                            <th>@lang('admin.name_en')</th>
                                            <th>@lang('main.numbers_users')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.options')</th>
                                        </tr>
                                        </thead>
                                        <tbody id="cities-list">
                                        @foreach($data['permissions_types'] as $index=>$permission)
                                            <tr>
                                                <td>{{ $index  + 1 }}</td>
                                                <td><a>{{ $permission->name_ar }}</a></td>
                                                <td><a>{{ $permission->name_en }}</a></td>
                                                <td>{{ $permission->permission_type_count ?? 0 }}</td>
                                                <td>
                                                    @if($permission->status == 1)
                                                        <button
                                                            class="custom-badge status-green">@lang('admin.Active')</button>
                                                    @else
                                                        <button
                                                            class="custom-badge status-red">@lang('admin.In Active')</button>
                                                    @endif
                                                </td>

                                                <td class="d-flex gap-2">
                                                    <a href="javascript:void(0)" class="add-table-invoice"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#edit_permission_{{$permission->id}}"
                                                       title="Edit"><i
                                                            class="fa fa-pen-to-square"></i></a>
                                                    <a href="javascript:void(0)" class="add-table-invoice danger"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#delete_permission_{{$permission->id}}"
                                                       title="Delete"><i class="fa fa-trash-can"></i></a>

                                                    <!-- Delete Department Modal -->
                                                    <div id="delete_permission_{{$permission->id}}"
                                                         class="modal fade delete-modal" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form class="needs-validation" novalidate=""
                                                                      action="{{route('permissionsTypes.destroy',$permission->id) }}"
                                                                      method="POST">
                                                                    {{ method_field('delete') }}
                                                                    {{ csrf_field() }}
                                                                    <div class="modal-body text-center">
                                                                        <img src="/assets/img/sent.png" alt=""
                                                                             width="50" height="46">
                                                                        <h3>@lang('admin.Are you sure you want to delete this?')</h3>
                                                                        <div class="m-t-20"><a href="#"
                                                                                               class="btn btn-white"
                                                                                               data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                            <button type="submit"
                                                                                    class="btn btn-danger">@lang('admin.delete')</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Delete Department Modal End -->

                                                    <!-- Add Department Modal -->
                                                    <div class="modal custom-modal modal-bg fade bank-details"
                                                         id="edit_permission_{{$permission->id}}" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-2 px-3">
                                                                    <div class="form-header text-start mb-0">
                                                                        <h4 class="mb-0">@lang('main.edit_new_permission')</h4>
                                                                    </div>
                                                                    <button type="button" class="close"
                                                                            data-bs-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-start py-4 px-3">
                                                                    <form id="add_department_form"
                                                                          action="{{ route('permissionsTypes.update',$permission->id) }}"
                                                                          method="POST">
                                                                        @csrf
                                                                        {{ method_field('put') }}
                                                                        <div class="bank-inner-details">

                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <div class="form-group local-forms">
                                                                                        <label>@lang('admin.name_ar')
                                                                                            <span
                                                                                                class="login-danger">*</span></label>
                                                                                        <input class="form-control"
                                                                                               placeholder="@lang('admin.name_ar')"
                                                                                               name="name_ar"
                                                                                               value="{{$permission->name_ar}}"
                                                                                               required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-12">
                                                                                    <div class="form-group local-forms">
                                                                                        <label>@lang('admin.name_en')
                                                                                            <span
                                                                                                class="login-danger">*</span></label>
                                                                                        <input class="form-control"
                                                                                               placeholder="@lang('admin.name_en')"
                                                                                               name="name_en"
                                                                                               value="{{$permission->name_en}}"
                                                                                               required>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-12">
                                                                                    <div class="form-group local-forms modal-select">
                                                                                        <label>@lang('admin.status') <span class="login-danger">*</span></label>
                                                                                        <select class="form-control select" name="status">
                                                                                            <option selected="true"
                                                                                                    disabled="disabled">@lang('admin.select') @lang('admin.status')</option>
                                                                                            <option value="1" @if($permission->status == 1) selected @endif>@lang('admin.Active')</option>
                                                                                            <option value="0" @if($permission->status == 0) selected @endif>@lang('admin.In Active')</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>


                                                                            </div>

                                                                        </div>

                                                                        <div class="modal-footer p-3">

                                                                            <div class="bank-details-btn">
                                                                                <a href="javascript:void(0);"
                                                                                   data-bs-dismiss="modal"
                                                                                   class="btn bank-cancel-btn me-2">{{ trans('admin.cancel') }}</a>
                                                                                <button class="btn bank-save-btn"
                                                                                        type="submit">
                                                                                    {{ trans('admin.edit') }}
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Add Department Modal End -->
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accounts -->
            </div>
            <!-- Table Data End -->
        </div>
        <!-- /Page Content -->
    </div>

    <!-- Add Department Modal -->
    <div class="modal custom-modal modal-bg fade bank-details" id="add_permission" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">@lang('main.add_new_permission')</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="add_package_form" action="{{ route('permissionsTypes.store') }}"
                      method="POST">
                    @csrf

                    <div class="modal-body text-start py-4 px-3">
                        <div class="bank-inner-details">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group local-forms">
                                        <label>@lang('admin.name_ar') <span class="login-danger">*</span></label>
                                        <input class="form-control" placeholder="@lang('admin.name_ar')" name="name_ar"
                                               value="{{old('name_ar')}}" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group local-forms">
                                        <label>@lang('admin.name_en') <span class="login-danger">*</span></label>
                                        <input class="form-control" placeholder="@lang('admin.name_en')" name="name_en"
                                               value="{{old('name_en')}}" required>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group local-forms modal-select">
                                        <label>@lang('admin.status') <span class="login-danger">*</span></label>
                                        <select class="form-control select" name="status">
                                            <option selected="true"
                                                    disabled="disabled">@lang('admin.select') @lang('admin.status')</option>
                                            <option value="1">@lang('admin.Active')</option>
                                            <option value="0">@lang('admin.In Active')</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer p-3">

                        <div class="bank-details-btn">
                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn bank-cancel-btn me-2">{{ trans('admin.cancel') }}</a>
                            <button class="btn bank-save-btn" type="submit">{{ trans('admin.add') }}</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Department Modal End -->

@endsection
