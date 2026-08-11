@extends('layout_new.mainlayout')
@section('content')

    <!-- ========================
    Start Page Content
========================= -->

    <div class="page-wrapper">
        <!-- Start Content -->
        <div class="content">

            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0"> @lang('admin.Notifications') <span class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">@lang('main.Total Notifications') : {{ count($notifications) }}</span> </h4>
                </div>
                <div class="text-end d-flex">
                    <!-- dropdown-->
                    <div class="dropdown me-1">
                        <a href="javascript:void(0);" class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center"  data-bs-toggle="dropdown">
                            Export<i class="ti ti-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu p-2">
                            <li>
                                <a class="dropdown-item" href="#">Download as PDF</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Download as Excel</a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{route('notificationsList.create')}}" class="btn btn-primary ms-2 fs-13 btn-md"><i class="ti ti-plus me-1"></i>@lang('main.Create Notification') </a>
                </div>
            </div>
            <!-- End Page Header -->

            <div class=" d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set mb-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="table-search d-flex align-items-center mb-0">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn-searchset"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex table-dropdown mb-3 pb-1 right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="btn btn-white fs-14 py-1 bg-white border d-inline-flex text-dark align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-filter text-gray-5 me-1"></i>{{ trans('admin.Filters') }}
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown p-0" id="filter-dropdown">
                            <div class="d-flex align-items-center justify-content-between border-bottom filter-header">
                                <h5 class="mb-0 fw-bold">{{ trans('admin.Filter') }}</h5>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-danger text-decoration-underline">{{ trans('admin.Clear All') }}</a>
                                </div>
                            </div>
                            <form action="#">
                                <div class="filter-body pb-0">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 text-dark fs-14 fw-medium">{{ trans('admin.date') }}</label>
                                        <div class="input-icon-end position-relative">
                                            <input type="text" class="form-control datetimepicker" name="date_from" placeholder="dd/mm/yyyy">
                                            <span class="input-icon-addon" >
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">{{ trans('admin.status') }}</label>
                                        </div>
                                        <select class="select" name="status" required>
                                            <option value="1" selected>{{ trans('admin.Active') }}</option>
                                            <option value="0">{{ trans('admin.Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="filter-footer d-flex align-items-center justify-content-end border-top">
                                    <a href="javascript:void(0);" class="btn btn-light btn-md me-2" id="close-filter">{{ trans('admin.Close') }}</a>
                                    <button type="submit" class="btn btn-primary btn-md">{{ trans('admin.Filter') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14" data-bs-toggle="dropdown">
                            <span  class="me-1"> {{ trans('admin.Sort By') }} : </span>  {{ trans('admin.Recent') }}
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-2">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">{{ trans('admin.Recent') }}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">{{ trans('admin.Oldest') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-nowrap datatable">
                    <thead class="thead-light">
                    <tr>
                        <th>{{ trans('admin.name') }}</th>
                        <th>{{ trans('admin.Created Date') }}</th>
                        <th>{{ trans('admin.title') }}</th>
                        <th>{{ trans('admin.message') }}</th>
                        <th>{{ trans('admin.Status') }}</th>
                        <th>@lang('admin.created_by')</th>
                        <th>{{ trans('admin.Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($notifications as $index=>$notification)
                        <tr id="row-{{ $notification->id }}" class="text-center">
                            <td>{{  $notification->type == 0 ? trans('main.all') : null }}</td>
                            <td>{{ $notification->created_at->format('d M Y') ?? null }}</td>
                            <td>{{ $notification->title ?? null }}</td>
                            <td>{{ $notification->message ?? null }}</td>
                            <td><span class="badge @if($notification->is_read == 1) badge-soft-success border border-success @else badge-soft-danger border border-danger @endif  px-2 py-1 fs-13 fw-medium">@if($notification->is_read == 1) @lang('main.seen') @else @lang('main.not_seen') @endif </span></td>
                            <td>{{$notification->admin->name ?? null}}</td>
                            <td>
{{--                                <a href="javascript: void(0);" class="link-reset fs-18 p-1" data-bs-toggle="modal" data-bs-target="#edit_city_{{$notification->id}}"> <i class="ti ti-pencil"></i></a>--}}
                                <a href="javascript:void(0);"
                                   class="link-reset fs-18 p-1 delete-btn"
                                   data-route="{{route('notificationsList.destroy',$notification->id) }}"
                                   data-id="row-{{ $notification->id }}"
                                   data-bs-toggle="modal"
                                   data-bs-target="#genericDeleteModal">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>
        <!-- End Content -->

    </div>




    <!-- General Delete Modal -->
    <div class="modal fade" id="genericDeleteModal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form id="genericDeleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center position-relative z-1">
                        <img src="{{ URL::asset('build/img/bg/delete-modal-bg-01.png') }}" class="img-fluid position-absolute top-0 start-0 z-n1" alt="">
                        <img src="{{ URL::asset('build/img/bg/delete-modal-bg-02.png') }}" class="img-fluid position-absolute bottom-0 end-0 z-n1" alt="">
                        <div class="mb-3">
                            <span class="avatar avatar-lg bg-danger text-white"><i class="ti ti-trash fs-24"></i></span>
                        </div>
                        <h5 class="fw-bold mb-1">{{ trans('admin.Delete Confirmation') }}</h5>
                        <p class="mb-3">{{ trans('admin.Are you sure want to delete?') }}</p>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">{{ trans('admin.Cancel') }}</button>
                            <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">{{ trans('admin.Yes, Delete') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
