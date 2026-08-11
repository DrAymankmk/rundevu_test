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
                            <li class="breadcrumb-item active">@lang('admin.doctor.Drug lists')
                                ( {{ $data['drugs']->total() }} )
                            </li>
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
                                            <h3>@lang('admin.doctor.Drug lists')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                               placeholder="Search here">
                                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg"
                                                                            alt=""></a>
                                                    </form>
                                                </div>
                                                <div class="add-group">
                                                    <a href="{{ route('create-drug') }}"
                                                       class="btn btn-primary add-pluss ms-2"><img
                                                            src="/assets/img/icons/plus.svg" alt=""></a>
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
                                        <th>@lang('admin.name_en')</th>
                                        <th>@lang('admin.name_ar')</th>
                                        <th>@lang('admin.status')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['drugs'] as $index=>$drug)
                                        <tr>
                                            <td style="display: none">{{ $index + 1 }}</td>
                                            <td class="profile-image"><a href="{{ route('update-drug', $drug->id) }}"> {{ $drug->name_en }}</a></td>
                                            <td class="profile-image"><a href="{{ route('update-drug', $drug->id) }}"> {{ $drug->name_ar }}</a>
                                            </td>
                                            @if($drug->status == 1)
                                                <td>
                                                    <button
                                                        class="custom-badge  status-green ">@lang('admin.Active')</button>
                                                </td>
                                            @else
                                                <td>
                                                    <button
                                                        class="custom-badge status-pink ">@lang('admin.In Active') </button>
                                                </td>
                                            @endif
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                       data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('update-drug', $drug->id) }}"><i
                                                                class="fa-solid fa-pen-to-square m-r-5"></i> @lang('admin.edit')
                                                        </a>
                                                        <a class="dropdown-item" href="{{route('drug-sections',$drug->id)}}"><i
                                                                class="feather-eye m-r-5"></i> @lang('admin.doctor.drug_sections')
                                                        </a>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                           data-bs-target="#delete_drug{{$drug->id}}"><i
                                                                class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')
                                                        </a>
                                                    </div>
                                                </div>

                                                <div id="delete_drug{{$drug->id}}" class="modal fade delete-modal"
                                                     role="dialog">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form class="needs-validation" novalidate=""
                                                                  action="{{route('destroy-drug',$drug->id) }}"
                                                                  method="POST">
                                                                {{ method_field('delete') }}
                                                                {{ csrf_field() }}
                                                                <div class="modal-body text-center">
                                                                    <img src="/assets/img/sent.png" alt="" width="50"
                                                                         height="46">
                                                                    <h3>@lang('admin.confirm_delete')</h3>
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

        </div>
    </div>
@endsection
