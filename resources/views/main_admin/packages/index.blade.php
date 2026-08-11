@extends('layout_new.mainlayout')
{{--@extends('includes_admin.mainlayout')--}}
@section('content')
    <style>
        .package-status-select label {
            z-index: 2 !important;
        }

        .package-status-select .select2-container {
            width: 100% !important;
        }

        .bank-details .select2-container--open {
            z-index: 1060 !important;
        }

        .bank-details .select2-dropdown {
            z-index: 1061 !important;
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
                            <li class="breadcrumb-item active p-0">@lang('main.packages')</li>
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
                                <form action="{{ route('packages.index') }}" method="get">
                                    <div class="row align-items-center gap-2 d-md-flex d-block">
                                        <div class="col">
                                            <div class="doctor-table-blk">
                                                <h3>@lang('main.packages') ({{ count($data['packages']) }} )</h3>
                                            </div>
                                        </div>
                                        <div class="col-auto text-end py-2 ms-auto download-grp add-group sm:flex-row flex-col">
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                               data-bs-target="#add_package"
                                               class="btn btn-primary text-nowrap w-100 border"><span>@lang('main.add_new_package')</span></a>
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
                                            <th>@lang('main.duration')</th>
                                            <th>@lang('main.price')</th>
                                            <th>@lang('main.numbers_users')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.options')</th>
                                        </tr>
                                        </thead>
                                        <tbody id="cities-list">
                                        @foreach($data['packages'] as $index=>$package)
                                            <tr>
                                                <td>{{ $index  + 1 }}</td>
                                                <td><a>{{ $package->name_ar }}</a></td>
                                                <td><a>{{ $package->name_en }}</a></td>
                                                <td>{{ $package->duration }}</td>
                                                <td>{{ $package->price }}</td>
                                                <td>{{ $package->subscriptions_package_users_count ?? 0 }}</td>
                                                <!-- <td>
                                                    @if($package->status == 1)
                                                        <button
                                                            class="custom-badge status-green">@lang('admin.Active')</button>
                                                    @else
                                                        <button
                                                            class="custom-badge status-red">@lang('admin.In Active')</button>
                                                    @endif
                                                </td> -->
                            <td><span class="badge @if($package->status == 1) badge-soft-success border border-success @else badge-soft-danger border border-danger @endif  px-2 py-1 fs-13 fw-medium">@if($package->status == 1) @lang('admin.Active') @else @lang('admin.Inactive') @endif </span></td>

                                                <td class="d-flex gap-2">
                                                    <a href="javascript:void(0)" class="" style="padding:10px; border-radius:50%;"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#edit_package_{{$package->id}}"
                                                       title="Edit"><i
                                                            class="fa fa-pen-to-square"></i></a>
                                                    <a href="javascript:void(0)" class="danger" style="padding:10px; border-radius:50%;"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#delete_package_{{$package->id}}"
                                                       title="Delete"><i class="fa fa-trash-can"></i></a>

                                                    <!-- Delete Department Modal -->
                                                    <div id="delete_package_{{$package->id}}"
                                                         class="modal fade delete-modal" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form class="needs-validation" novalidate=""
                                                                      action="{{route('packages.destroy',$package->id) }}"
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

                                                    <!-- Edit Package Modal -->
                                                    <div class="modal custom-modal modal-bg fade bank-details"
                                                         id="edit_package_{{$package->id}}" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header py-2 px-3">
                                                                    <div class="form-header text-start mb-0">
                                                                        <h4 class="mb-0">@lang('main.edit_package')</h4>
                                                                    </div>
                                                                    <button type="button" class="close"
                                                                            data-bs-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-start py-4 px-3">
                                                                    <form action="{{ route('packages.update',$package->id) }}"
                                                                          method="POST">
                                                                        @csrf
                                                                        {{ method_field('put') }}
                                                                        <div class="bank-inner-details">
                                                                            @include('main_admin.packages.partials.form-fields', [
                                                                                'package' => $package,
                                                                                'modalKey' => 'edit-' . $package->id,
                                                                            ])
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
                                                    <!-- Edit Package Modal End -->
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
            <!-- Table Data End -->
        </div>
        <!-- /Page Content -->
    </div>

    <!-- Add Package Modal -->
    <div class="modal custom-modal modal-bg fade bank-details" id="add_package" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">@lang('main.add_new_package')</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="add_package_form" action="{{ route('packages.store') }}" method="POST">
                    @csrf

                    <div class="modal-body text-start py-4 px-3">
                        <div class="bank-inner-details">
                            @include('main_admin.packages.partials.form-fields', [
                                'modalKey' => 'add',
                                'package' => null,
                            ])
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
    <!-- Add Package Modal End -->

@endsection

@push('scripts')
<script>
    (function () {
        function initPackageModalSelects($modal) {
            $modal.find('select.select').each(function () {
                var $el = $(this);
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.select2('destroy');
                }
                $el.select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    dropdownParent: $modal
                });
            });
        }

        $(document).on('shown.bs.modal', '.bank-details', function () {
            initPackageModalSelects($(this));
        });
    })();
</script>
@endpush
