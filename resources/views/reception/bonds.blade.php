@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .modal-select label {
            z-index: 99999 !important;
        }
        .select2-container--default.select2-container--open {
            z-index: 9999 !important;
        }
    </style>
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('patients') }}">@lang('admin.reception.reception') </a></li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.reception.bonds')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center gap-2 d-md-flex d-block">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.reception.bonds')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <!-- <form> -->
                                                    <input onkeyup="search(event)" type="text" class="form-control" placeholder="ابحث هنا">
                                                    <a class="btn"><img src="/assets/img/icons/search-normal.svg" alt=""></a>
                                                    <!-- </form> -->
                                                </div>
                                                <div class="add-group">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#add_bond" class="btn btn-primary add-pluss"><img src="/assets/img/icons/plus.svg" alt=""></a>
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh "><img src="/assets/img/icons/re-fresh.svg" alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                            <th>المريض</th>
                                            <th>رقم الملف</th>
                                            <th>المبلغ</th>
                                            <th>التاريخ</th>
                                            <th>الحساب</th>
                                            <th>ملحوظات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['bonds'] as $bond_item)
                                        <tr>
                                            <td class="profile-image"><a href="profile.html"><img width="28" height="28" src="{{ $bond_item->user->image }}" class="rounded-circle m-r-5" alt="">
                                                {{ $bond_item->user->name }}</a></td>
                                            <td>{{ $bond_item->user->file_number ?? null }}</td>
                                            <td>{{ $bond_item->price ?? null }} @lang('admin.SAR')</td>
                                            <td>{{ $bond_item->created_at}}</td>
                                            <td>@lang('admin.'.$bond_item->account_type)</td>
                                            <td class="text-wrap" style="width: 800px; min-width: 500px;">{{ $bond_item->notes ?? null}}</td>
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
    </div>

    <!-- Add Bond Modal -->
    <div class="modal custom-modal modal-bg fade bank-details" id="add_bond" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="post"  action="{{ route('create-bond') }}"
                  enctype="multipart/form-data" class="" class="was-validated needs-validation">
                @csrf
            <div class="modal-content">
                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">@lang('admin.add_bond')</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-start py-4 px-3">
                    <div class="bank-inner-details">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label for="patient">@lang('admin.patient_name')<span class="login-danger">*</span></label>
                                    <select class="form-control select" name="patient_id" required>
                                        <option selected="true"
                                                disabled="disabled">@lang('admin.select')</option>
                                        @foreach($data['patients'] as $patient)
                                            <option value="{{ $patient->id }}">{{$patient->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>@lang('admin.amount') <span class="login-danger">*</span></label>
                                    <input class="form-control" name="price" type="number" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>@lang('admin.notes')  <span class="login-danger">*</span></label>
                                    <textarea class="form-control" name="notes" style="min-height: 87px;" placeholder="@lang('admin.enter_text_here')" rows="3" cols="30" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms modal-select mb-0">
                                    <label>@lang('admin.account') <span class="login-danger">*</span></label>
                                    <select class="form-control select" name="account_type">
                                        <option selected="true" disabled="disabled">@lang('admin.select')  @lang('admin.account')</option>
                                        <option value="register_in_safe">@lang('admin.register_in_safe')</option>
                                        <option value="register_with_card">@lang('admin.register_with_card')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <div class="bank-details-btn">
                        <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn bank-cancel-btn me-2">@lang('admin.cancel')</a>
                        <button type="submit" class="btn btn-primary submit-form me-2">@lang('admin.save')</button>

                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!-- Add Bond Modal End -->
    <script src="/reception/assets/js/jquery.js"></script>
    <script src="/reception/assets/js/select2.js"></script>
    <script>
        $(document).ready(function () {
            $("#patient").select2();
        });
        function search(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                document.getElementsByClassName('table-loader')[0].style.display = 'flex';
                setTimeout(function () {
                    document.getElementsByClassName('table-loader')[0].style.display = 'none';
                }, 3000)
            }
        }
    </script>

@endsection
