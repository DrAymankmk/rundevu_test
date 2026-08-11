@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .invoice-content {
            font-family: 'Cairo';
            max-width: 1000px;
            margin: auto;
        }
        .invoice-data {
            border: 1px solid #E5E5E5;
            padding: 10px 12px;
        }
        .invoice-data p {
            margin-bottom: 0.10rem;
            font-weight: 600;
        }
        .invoice-data span:first-child, .invoice-data span:last-child {
            color: #1B2559;
            margin: 0;
            /* white-space: nowrap; */
        }
        .invoice-data span:first-child {
            direction: rtl;
        }
        .invoice-data span:last-child {
            direction: ltr;
        }
        .invoice-data span:nth-child(2) {
            color: rgba(51, 53, 72);
            margin: 0px 5px;
            text-align: center;
        }
        .table-bordered th {
            font-weight: 500 !important;
            color: #1B2559;
            white-space: nowrap;
        }
        .table-bordered td {
            font-weight: 500 !important;
            color: rgba(51, 53, 72);
        }
        .invoice-counts p {
            margin: 0;
        }
        @media (max-width: 767px) {
            .invoice-content {
                /* width: 300px; */
                margin: auto;
            }
            .invoice-data {
                padding: 7px;
            }
            .invoice-data p {
                font-weight: 500;
                font-size: 14px;
            }
            .invoice-counts, .invoice-counts a {
                font-size: 14px !important;
            }
            /* .table-responsive {
                max-width: 300px;
            } */
            .table-bordered th {
                font-size: 14px;
                padding: 4px;
            }
            .table-bordered td {
                font-size: 14px;
                padding: 4px;
                /* word-break: break-word; */
            }
        }
        @page {
            margin: 0;
        }
        @media print {
            @page {
                size: letter;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .page-wrapper, .page-wrapper .content, .invoice-counts a {
                margin: 0 !important;
                padding: 0 !important;
            }
            .invoice-content {
                width: 100%;
                margin: auto;
            }
            .invoice-data {
                padding: 7px;
            }
            /* .invoice-data p {
                font-weight: 500;
                font-size: 12px;
            } */
            /* .invoice-counts, .invoice-counts a {
                font-size: 14px !important;
            } */
            /* .table-responsive {
                max-width: 300px;
            } */
            /* .table-bordered th {
                font-size: 12px;
                padding: 4px;
            } */
            .table-bordered td {
                /* font-size: 12px;
                padding: 4px; */
                word-break: break-word;
            }
            .custom-table tr {
                white-space: wrap;
            }
            .custom-table tr th {
                white-space: nowrap;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header no-print">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('invoices') }}">@lang('admin.reception.invoices')</a></li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.show_invoice')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice-content">
                                <div class="invoice-head-clinic" style="direction: ltr;">
                                    <div class="d-flex justify-content-between flex-wrap gap-1 gap-x-4">
                                        <div class="invoice-counts gap-1">
                                            <img src="{{$invoice->doctor->owner->image ?? null}}" width="20" height="20" alt=""> <span class="fw-bold">{{$invoice->doctor->owner->name ?? null}}</span>
                                        </div>
                                        <div class="invoice-counts gap-2 flex-wrap" style="margin-left: auto;">
                                            <p><span>{{ $invoice->invoice_number }}</span></p>
                                            <p class="no-print"><a href="javascript:;" class="status-green px-1 py-0 m-0">@lang('admin.invoice_number')</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2" style="direction: rtl;">
                                    <div class="invoice-data">
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>رقم الدخول:</span>
                                            <span>{{ $invoice->id }}</span>
                                            <span>Token No:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>الرقم الضريبى:</span>
                                            <span>541515153313515</span>
                                            <span>Vat Reg. No:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>التاريخ:</span>
                                            <span>{{ $invoice->created_at }}</span>
                                            <span>Date:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>فاتورة:</span>
                                            <span>{{ $invoice->invoice_number }}</span>
                                            <span>Invoice No:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>رقم الملف:</span>
                                            <span>{{ $invoice->user->file_number }}</span>
                                            <span>MRN:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>اسم المريض:</span>
                                            <span>{{ $invoice->user->name }}</span>
                                            <span>Pat. Name:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>اسم الطبيب:</span>
                                            <span>{{ $invoice->doctor->name ?? null }}</span>
                                            <span>Doctor:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>اسم العيادة:</span>
                                            <span>{{ $invoice->doctor->owner->name }}</span>
                                            <span>Clinic:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>الجنسية:</span>
                                            <span>{{ app()->getLocale() == 'en' ? $invoice->user->nationality->name_en :  $invoice->user->nationality->name_ar }}</span>
                                            <span>Nationality:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>رقم الهوية:</span>
                                            <span>{{ $invoice->user->file_number }}</span>
                                            <span>Pat.ID:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>البوليصة:</span>
                                            <span>{{ $invoice->user->bill_number ?? null }}</span>
                                            <span>Policy:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>شركة التأمين:</span>
                                            <span>{{ app()->getLocale() == 'en' ? $invoice->user->company->name_en ?? null :  $invoice->user->company->name_ar ?? null }}</span>
                                            <span>Insurance Co:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>المستخدم:</span>
                                            <span>{{ $invoice->user->id ?? null }}</span>
                                            <span>User ID:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>هاتف</span>
                                            <span>{{ $invoice->user->phone ?? null }}</span>
                                            <span>Phone No:</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="table-responsive mb-2" style="direction: rtl;">
                                    <table class="text-center table table-bordered mb-0">
                                        <thead>
                                        <tr>
                                            <th>الرمز</br>Code</th>
                                            <th>الكمية</br>Qty</th>
                                            <th>السعر</br>Price</th>
                                            <th>الخصم</br>Disc</th>
                                            <th>ضريبة</br>VAT</th>
                                            <th>اجمالى</br>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($invoice->services as $service)
                                        <tr>
                                            <td>{{ $service->services->code ?? null }}</td>
                                            <td>{{ $service->qty }}</td>
                                            <td>{{ $service->price }}</td>
                                            <td>{{ $service->discount ?? 0 }}</td>
                                            <td>{{ $service->tax  ?? 0 }}</td>
                                            <td>{{ $service->price - $service->discount }}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mb-2" style="direction: rtl;">
                                    <div class="invoice-data">
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>إجمالى قبل الخصم:</span>
                                            <span>{{ $invoice->services->sum('price') ?? 0}}</span>
                                            <span>Total Before Disc:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>الخصم:</span>
                                            <span> {{$invoice->services->sum('discount') }}</span>
                                            <span>Discount:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>إجمالى بعد الخصم:</span>
                                            <span>{{  $invoice->services->sum('price') -  $invoice->services->sum('discount') }}</span>
                                            <span>Total After Disc:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>إجمالى مبلغ التحمل:</span>
                                            <span>{{ $invoice->total_price * ((100-$invoice->company_total_deductible) / 100) ?? 0 }}</span>
                                            <span>Total Deductible:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>أضف 15% ضريبة</span>
                                            <span>{{ $invoice->patient_tax ?? 0 }}</span>
                                            <span>Items with 15% VAT:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>أضف 0% ضريبة</span>
                                            <span>20.00</span>
                                            <span>Items with 0% VAT:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>ضريبة للشركة:</span>
                                            <span>{{ $invoice->company_tax ?? 0 }}</span>
                                            <span>Company VAT:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>ضريبة للمريض:</span>
                                            <span>{{ $invoice->patient_tax ?? 0 }}</span>
                                            <span>Patient VAT:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>إجمالى المبلغ على الشركة:</span>
                                            <span>{{ $invoice->total_price * ($invoice->company_total_deductible / 100) ?? 0 }}</span>
                                            <span>Company Tot+VAT:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>إجمالى المبلغ المدفوع:</span>
                                            <span>{{ $invoice->total_amount_paid }}</span>
                                            <span>Total Amount Paid:</span>
                                        </p>
                                        <p class="d-flex align-items-center justify-content-between">
                                            <span>المبلغ المتبقى:</span>
                                            <span>{{ $invoice->total_price - $invoice->total_amount_paid }}</span>
                                            <span>Remaining Amount:</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="rounded-1" style="border: 1px solid #000; padding: 3px; width: fit-content; margin: auto;" id="qrcode"></div>
                                    <!-- <p class="text-center mb-0">
                                        <span class="fw-bold">Reg By:</span>
                                        <span>1503</span>
                                    </p> -->
                                </div>
                                <div class="invoice-data mb-3">
                                    <p class="m-0">
                                        <span>Note/ملاحظة:</span>
                                        <span style="white-space: wrap;">{{ $invoice->other_info ?? null }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 no-print">
                                <div class="doctor-submit d-flex justify-content-end">
                                    <a href="javascript:;" class="btn btn-primary submit-form me-2">@lang('admin.send_invoice')</a>
                                    <a href="javascript:;" onclick="printInvoice()" class="btn btn-primary cancel-form"><i class="feather-printer me-2"></i>@lang('admin.print')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="/reception/assets/js/qr-code.js"></script>
    <script>
        function printInvoice() {
            window.print()
        }
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $invoice->invoice_number }}",
            width: 100,
            height: 100,
            colorDark : "#000",
            colorLight : "#fff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>


@endsection
