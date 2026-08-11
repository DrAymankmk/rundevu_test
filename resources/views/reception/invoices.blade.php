@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('patients') }}">@lang('admin.reception.reception') </a></li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.reception.invoices')</li>
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
                                            <h3>@lang('admin.reception.invoices')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <!-- <form> -->
                                                    <input onkeyup="search(event)" type="text" class="form-control"
                                                           placeholder="ابحث هنا">
                                                    <a class="btn"><img src="/assets/img/icons/search-normal.svg"
                                                                        alt=""></a>
                                                    <!-- </form> -->
                                                </div>
                                                <div class="add-group">
                                                    <a href="{{ route('create-invoice') }}" class="btn btn-primary add-pluss"><img
                                                            src="/assets/img/icons/plus.svg" alt=""></a>
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh "><img
                                                            src="/assets/img/icons/re-fresh.svg" alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end py-2 ms-auto download-grp">
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-04.svg" alt=""></a>

                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <div class="staff-search-table">
                                <form
                                    action="{{route('invoices')}}"
                                    method="POST" enctype="multipart/form-data">
                                    {{ method_field('Get') }}
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.From') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_from"
                                                       value="{{old('date_from')}}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="form-group local-forms cal-icon">
                                                <label>@lang('admin.To') </label>
                                                <input class="form-control datetimepicker" type="text" name="date_to"
                                                       value="{{old('date_to')}}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4 ">
                                            <div class="form-group local-forms">
                                                <label>@lang('admin.payment_status') </label>
                                                <select class="form-control select" name="payment_status">
                                                    <option selected
                                                            disabled>@lang('admin.select')  @lang('admin.payment_status')</option>
                                                    <option value="paid">@lang('admin.paid')</option>
                                                    <option value="un_paid">@lang('admin.un_paid')</option>
                                                    <option
                                                        value="partially_paid">@lang('admin.partially_paid')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4 ms-auto">
                                            <div class="doctor-submit">
                                                <button  type="submit"
                                                        class="btn btn-primary submit-list-form me-2">@lang('admin.filter')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="position-relative">
                                <div class="table-loader">
                                    <div class="spinner"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>رقم الفاتورة</th>
                                            <th>المريض</th>
                                            <th>نوع المريض</th>
                                            <th>الطبيب</th>
                                            <th>المبلغ</th>
                                            <th>المديونية</th>
                                            <th>اليوم</th>
                                            <th>التاريخ</th>
                                            <th>الحالة</th>
                                            <th>خيارات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['invoices'] as $index=>$invoice)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td><a href="{{ route('invoice-view', $invoice->id) }}">{{$invoice->invoice_number}}</a></td>
                                                <td class="profile-image"><a href="{{ route('edit-patient', $invoice->user_id) }}"><img width="28"
                                                                                                      height="28"
                                                                                                      src="{{$invoice->user->image}}"
                                                                                                      class="rounded-circle m-r-5"
                                                                                                      alt="">{{$invoice->user->name}}
                                                    </a></td>
                                                <td>@if($invoice->user->company_id != null)
                                                        @lang('admin.insurance')
                                                    @else
                                                        @lang('admin.cash')
                                                    @endif</td>

                                                <td>{{ $invoice->doctor->name }}</td>
                                                <td>{{ $invoice->total_price ?? 0 }} @lang('admin.SAR')</td>
                                                <td>{{ $invoice->total_price - $invoice->total_amount_paid ?? 0 }} @lang('admin.SAR')</td>
                                                <td>{{ date('l', strtotime($invoice->created_at)) }}</td>
                                                <td>{{ date('F j Y',strtotime($invoice->created_at)) }}</td>
                                                <td>
                                                    @if($invoice->payment_status == 'paid')
                                                        <button
                                                            class="custom-badge status-green">@lang('admin.'.$invoice->payment_status)</button>
                                                    @elseif($invoice->payment_status == 'un_paid')
                                                        <button
                                                            class="custom-badge status-pink">@lang('admin.'.$invoice->payment_status)</button>
                                                    @else
                                                        <button
                                                            class="custom-badge status-orange">@lang('admin.'.$invoice->payment_status)</button>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                           data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="fa fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{ route('invoice-view', $invoice->id) }}"><i
                                                                    class="feather-eye m-r-5"></i> @lang('admin.show')</a>

                                                            <a class="dropdown-item invoice-download-link" data-invoice-id="{{ $invoice->id }}" href="javascript:;"><i class="feather-download m-r-5"></i> @lang('admin.download')</a>

                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                               data-bs-target="#delete_invoice"><i
                                                                    class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')</a>
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

    </div>
    <div id="delete_invoice" class="modal fade delete-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="assets/img/sent.png" alt="" width="50" height="46">
                    <h3>هل أنت متأكد أنك تريد حذف هذا؟</h3>
                    <div class="m-t-20"><a href="#" class="btn btn-white" data-bs-dismiss="modal">أغلق</a>
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function search(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                document.getElementsByClassName('table-loader')[0].style.display = 'flex';
                setTimeout(function () {
                    document.getElementsByClassName('table-loader')[0].style.display = 'none';
                }, 3000)
            }
        }

        function filter() {
            document.getElementsByClassName('table-loader')[0].style.display = 'flex';
            setTimeout(function () {
                document.getElementsByClassName('table-loader')[0].style.display = 'none';
            }, 3000)
        }

        document.querySelectorAll('.invoice-download-link').forEach(function(link) {
            link.addEventListener('click', function(event) {
                // Prevent the default behavior of the link
                event.preventDefault();
                // Retrieve the invoice ID from the data attribute
                var invoiceId = this.getAttribute('data-invoice-id');
                // Perform any action with the invoice ID, such as initiating the download
                // For example, you can use window.location to navigate to the download URL with the invoice ID
                window.location.href = '/admin/invoice-view/' + invoiceId; // Replace '/download/invoice/' with your actual download route
            });
        });
    </script>

@endsection
