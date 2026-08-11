@extends('includes_admin.mainlayout')
@section('content')
    <style>
        .delete {
            background-color: #ea545520;
            display: none;
        }

        .delete i {
            color: #ea5455 !important;
        }

        .table.custom-table > tbody > tr > td,
        .table.custom-table > tbody > tr > th,
        .table.custom-table > tfoot > tr > td,
        .table.custom-table > tfoot > tr > th,
        .table.custom-table > thead > tr > td,
        .table.custom-table > thead > tr > th {
            padding: 8px;
        }

        .table-select {
            border: 2px solid rgba(46, 55, 164, 0.1) !important;
            border-radius: 10px !important;
            padding: 10px 15px !important;
            position: unset !important;
            width: 100% !important;
            font-size: 14px;
            cursor: pointer;
        }

        .table-select:focus {
            border-color: #2e37a4 !important;
        }

        .table-select > option[disabled] {
            color: #676767;
        }

        .table-select + span {
            display: none;
        }
    </style>
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('invoices') }}">@lang('admin.reception.invoices')</a>
                            </li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.reception.create_invoice')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">

                    <div class="card ">
                        <div class="card-body">
                            <form method="post" action="{{ route('add-invoice') }}"
                                  enctype="multipart/form-data" class="" class="was-validated needs-validation">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-heading">
                                            <h4>@lang('admin.reception.create_invoice')</h4>
                                        </div>
                                    </div>
                                    <input type="hidden" name="reservation_id" value="{{$reservation->id}}">
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label for="patient">@lang('admin.patient_name') <span class="login-danger">*</span></label>
                                            <select id="patient" name="patient_id" class="form-control" required>
                                                <option value="">@lang('admin.select')</option>
                                                    <option value="{{ $reservation->user->id }}" selected>{{$reservation->user->name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms cal-icon">
                                            <label>@lang('admin.dob') <span class="login-danger">*</span></label>
                                            <input class="form-control datetimepicker" name="dob" id="dob" type="text" value="{{$reservation->user->dob}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label for="specialist_id"
                                                   class="specialist_id">@lang('admin.specialization')</label>
                                            <select class="form-control select" id="specialist_id" required>
                                                @foreach($data['specializations'] as $specialist)

                                                    <option value="{{ $specialist->specialty_id }}" @if($specialist->specialty_id == $reservation->doctor->specialties[0]['id'] ?? null ) selected @endif>{{ app()->getLocale() == 'en' ? $specialist->specialties->name_en : $specialist->specialties->name_ar }}  </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.Doctor')</label>
                                            <select class="form-control select" id="doctor_id" name="doctor_id" required >
                                                <option value="{{ $reservation->doctor_id }}">{{$reservation->doctor->name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.invoice_date')<span class="login-danger">*</span></label>
                                            <input  class="form-control" type="date" name="date" min="{{date('Y-m-d',strtotime($reservation->created_at))}}" value="{{ date('Y-m-d',strtotime($reservation->created_at)) }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.file_number') <span
                                                    class="login-danger">*</span></label>
                                            <input class="form-control" id="file_number" type="text" placeholder="@lang('admin.file_number')" value="{{$reservation->user->file_number ?? null}}" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label for="payment_method">@lang('admin.payment_method') <span
                                                    class="login-danger">*</span></label>
                                            <select class="form-control select" name="payment_method" id="payment_method">
                                                <option selected
                                                        disabled>@lang('admin.select') @lang('admin.payment_method')</option>
                                                @foreach($data['payment_method'] as $payment)
                                                    <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.select')@lang('admin.payment_status') <span class="login-danger">*</span></label>
                                            <select class="form-control select" name="payment_status">
                                                <option selected disabled>@lang('admin.select')  @lang('admin.payment_status')</option>
                                                <option value="paid">@lang('admin.paid')</option>
                                                <option value="un_paid">@lang('admin.un_paid')</option>
                                                <option value="partially_paid">@lang('admin.partially_paid')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.payment_number')<span class="login-danger">*</span></label>
                                            <input class="form-control" placeholder="@lang('admin.payment_number')" name="payment_number" value="{{old('payment_number')}}">
                                        </div>
                                    </div>

                                    <div class="col-12 m-t-20">
                                        <div class="row">
                                            <div class="col-md-4 col-xl-3" style="min-width: 176px;">
                                                <div id="qrCodeContainer"
                                                     class="m-auto p-2 d-flex justify-content-center rounded-3 m-b-30">
                                                    <div class="rounded-1" style="border: 2px solid #fff; padding: 6px;"
                                                         id="qrcode"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-xl-9">
                                                <div class="row">
                                                    <div class="col-lg-4 col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label style="font-size: 14px;">@lang('admin.price')</label>
                                                            <input type="text" class="form-control" id="price" name="price" value="{{old('price')}}"  required>
                                                        </div>
                                                    </div>
{{--                                                    <div class="col-lg-3 col-sm-6">--}}
{{--                                                        <div class="form-group mb-2">--}}
{{--                                                            <label--}}
{{--                                                                style="font-size: 14px;">@lang('admin.discount')</label>--}}
{{--                                                            <input type="number" class="form-control" value="0" id="discount" name="discount" readonly  required>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
                                                    <div class="col-lg-4 col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.patient_tax')</label>
                                                            <input type="text" readonly class="form-control" name="patient_tax" id="patient_tax"  value="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label style="font-size: 14px;">@lang('admin.total')</label>
                                                            <input type="text" class="form-control" name="reservation_total" id="reservation_total" value="{{old('total')}}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <input type="hidden" id="company_price" value="{{$company_cost}}">
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.patient_cost')</label>
                                                            <input type="hidden" id="company_percentage" >
                                                            <input type="text" class="form-control" name="patient_cost"  id="patient_cost" value="{{old('patient_cost')}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2">
                                                            <label style="font-size: 14px;">@lang('admin.company_cost')</label>
                                                            <input type="text" class="form-control" name="company_cost" id="company_cost" value="{{old('company_cost')}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.company_tax')</label>
                                                            <input type="text" class="form-control" name="company_tax" value="0" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.patient_total') </label>
                                                            <input type="text" class="form-control" name="patient_total" id="patient_total" value="{{old('patient_total')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.company_total')  </label>
                                                            <input type="text" class="form-control" name="company_total" id="company_total" value="{{old('company_total')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 m-t-30">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.other_info') <span class="login-danger">*</span></label>
                                            <textarea class="form-control" name="info" rows="3" cols="30"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label style="font-size: 14px;">@lang('admin.total_amount') </label>
                                            <input disabled class="form-control" placeholder="@lang('admin.total_amount')" id="total_amount" name="total_amount" value="{{old('total_amount')}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label style="font-size: 14px;">@lang('admin.amount_paid') </label>
                                            <input  class="form-control" placeholder="@lang('admin.amount_paid')" id="amount_paid" name="amount_paid" value="{{old('amount_paid')}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label style="font-size: 14px;">@lang('admin.remaining_amount') </label>
                                            <input disabled class="form-control" placeholder="@lang('admin.remaining_amount')" id="remaining_amount" name="remaining_amount" value="{{old('remaining_amount')}}">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit" class="btn btn-primary submit-form me-2">@lang('admin.save')</button>
                                            <button type="submit" class="btn btn-primary cancel-form">@lang('admin.cancel')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="/reception/assets/js/jquery.js"></script>
    <script src="/reception/assets/js/select2.js"></script>
    <script src="/reception/assets/js/qr-code.js"></script>

    <script>

        function addNewTableRow(tableID) {
            // show delete btn
            document.getElementsByClassName('delete')[0].style.display = 'flex';

            // access table
            var tbody = document.querySelector("#" + tableID + " tbody");
            var rows = tbody.querySelectorAll("tr");

            // copy the first TR of the table
            var newRow = rows[0].cloneNode(true);

            // create array of options to be added
            var optionsList = ["{{ trans('admin.service_type') }}", "{{ trans('admin.analysis') }}", "{{ trans('admin.service') }}", "{{ trans('admin.rays') }}"];

            // create and append select list
            var selectList = document.createElement("select");
            selectList.setAttribute('class', 'form-control select');

            // create and append the options
            for (var i = 0; i < optionsList.length; i++) {
                var option = document.createElement("option");
                option.value = optionsList[i];
                option.text = optionsList[i];
                if (i === 0) {
                    option.setAttribute('disabled', 'disabled')
                    option.setAttribute('selected', 'selected')
                }
                selectList.appendChild(option);
            }

            // remove new row inputs value
            for (let i = 1; i < newRow.cells.length - 1; i++) {
                newRow.cells[i].querySelectorAll('div > select').length > 0 ? newRow.cells[i].querySelector('div > select').setAttribute("id", i + 1) : '';
                newRow.cells[i].getElementsByTagName('input').length > 0 ? newRow.cells[i].getElementsByTagName('input')[0].value = "" : '';
            }

            // increment the last row number and apply it to the new row
            newRow.cells[0].innerHTML = ++rows.length;

            // add the new row
            tbody.appendChild(newRow);
        }

        function deleteTableRow(tableID) {
            // delete row
            event.target.closest("tr").remove();

            // access table
            var tbody = document.querySelector("#" + tableID + " tbody");
            var rows = tbody.querySelectorAll("tr");

            // hide delete btn
            if (rows.length === 1) document.getElementsByClassName('delete')[0].style.display = 'none';

            // reset rows number
            for (let i = 0; i < rows.length; i++) {
                rows[i].cells[0].innerHTML = i + 1;
            }
        }

        $(document).ready(function () {
            $("#patient").select2();
            $('#patient').on('change', function () {
                var patient_id = this.value;
                $('#qrcode').html('');
                $.ajax({
                    url: '{{ route('getQrCodeUser') }}?patient_id=' + patient_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        // Update the value of #dob based on the response
                        $('#dob').val(res.patient.dob); // Assuming res.patient.dob contains the value you want to set
                        $('#file_number').val(res.patient.file_number);
                        // $('#company_percentage').val(res.patient.company.amount ?? 0);
                        if (res.status == 'valid') {
                            colorLight = '#28C76F';
                        } else {
                            colorLight = '#EA5455';
                        }
                        ID_Number = res.patient.ID_Number ?? res.patient.id;
                        document.getElementById('qrCodeContainer').style.backgroundColor = colorLight
                        new QRCode(document.getElementById("qrcode"), {
                            text: ID_Number,
                            width: 120,
                            height: 120,
                            colorDark: "#fff",
                            colorLight: colorLight,
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    }
                });
            });


            $('#service_type').on('change', function () {
                var service_type = this.value;
                $('#service_name').html('');
                $.ajax({
                    url: '{{ route('getServices') }}?service_type=' + service_type,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.length > 0) { // all was ok
                            $('#service_name').append('<option value="">{{ trans('admin.select') }}</option>');
                            $.each(res, function (key, value) {
                                $('#service_name').append('<option value="' + value.id + '">' + value.name + '</option>');
                                $('#price').val('');
                            });
                        } else {
                            $('#service_name').html('<option value="">@lang('admin.no_data')</option>');
                            $('#price').val('');
                        }
                    }
                });
            });


            $('#service_name').on('change', function () {
                var service_name = this.value;
                $('#price').html('');
                $.ajax({
                    url: '{{ route('getServicesName') }}?service_name=' + service_name,
                    type: 'get',
                    dataType: 'json',
                    success: function (service) {
                        if (service) { // all was ok
                            var price = parseFloat(service.price);
                            $('#price').val(price);

                            var qty = parseFloat($('#qty').val()) || 1; // Use default value 1 if $('#qty').val() is undefined or null
                            var tax_percentage = (price * qty) * (parseFloat($('#tax_percentage').val()) / 100 ) || 0; // Use default value 0 if $('#tax_percentage').val() is undefined or null

                            // Set the value of tax input element
                            $('#tax').val(tax_percentage);

                            // Calculate the total price including tax
                            var service_price = price + tax_percentage;

                            // Set the value of services_price input element
                            $('#services_price').val(service_price);
                            $('#patient_tax').val(tax_percentage);
                            $('#services_total').val(service_price);
                            var company_cost = service_price - ((service_price) * (parseFloat($('#company_percentage').val()) / 100 )) || 0;

                            $('#company_cost').val(company_cost);


                            $('#patient_cost').val(service_price - company_cost);
                            $('#company_total').val(company_cost);
                            $('#patient_total').val(service_price - company_cost);
                            $('#total_amount').val(service_price);
                            var amount_paid = parseFloat($('#amount_paid').val()) || 0; // Use default value 1 if $('#qty').val() is undefined or null

                            $('#remaining_amount').val(service_price - amount_paid);
                        } else {
                            $('#price').html('<option value="">0</option>');
                        }
                    }
                });
            });


            $('#amount_paid').on('change', function() {
                // Get the value of input1
                var amount_paid = $(this).val();
                total_amount =   $('#total_amount').val();
                if(amount_paid > total_amount) {
                    alert('الميلغ المتبقى لا يمكته ان يكون اكبر من الاجمالى');
                    $('#amount_paid').empty();
                }
                $('#remaining_amount').val(total_amount - amount_paid);
            });

            $('#price').on('change', function() {
                var price = $(this).val();
               var patient_tax =   $('#patient_tax').val();
                $('#reservation_total').val(price - patient_tax);
                $discountedPrice = price - (price * ($('#company_price').val() / 100));
                $('#company_cost').val($discountedPrice);
                $('#patient_cost').val(price-$discountedPrice);
                $('#company_total').val($discountedPrice);
                $('#patient_total').val(price-$discountedPrice);
                $('#total_amount').val(price-$discountedPrice);
            });

        });
    </script>
@endsection
