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
                            <li class="breadcrumb-item"><a href="invoices.html">@lang('admin.reception.invoices')</a>
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
                                <input type="hidden" name="reservation_id" value="{{$reservation->id ?? null}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-heading">
                                            <h4>@lang('admin.reception.create_invoice')</h4>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label for="patient">@lang('admin.patient_name') <span class="login-danger">*</span></label>
                                            <select id="patient" name="patient_id" class="form-control" required>
                                                <option value="">@lang('admin.select')</option>
                                                @foreach($data['patients'] as $patient)
                                                    <option value="{{ $patient->id }}">{{$patient->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms cal-icon">
                                            <label>@lang('admin.dob') <span class="login-danger">*</span></label>
                                            <input class="form-control datetimepicker" name="dob" id="dob" type="text" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label for="specialist_id"
                                                   class="specialist_id">@lang('admin.specialization')</label>
                                            <select class="form-control select" id="specialist_id" required>
                                                <option value="">@lang('admin.select')</option>
                                                @foreach($data['specializations'] as $specialist)
                                                    <option
                                                        value="{{ $specialist->specialty_id }}">{{ app()->getLocale() == 'en' ? $specialist->specialties->name_en : $specialist->specialties->name_ar }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.Doctor')</label>
                                            <select class="form-control select" id="doctor_id" name="doctor_id" required >
                                                <option selected disabled>@lang('admin.select')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.invoice_date')<span class="login-danger">*</span></label>
                                            <input  class="form-control" type="date" name="date" value="{{ date('Y-m-d') }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.file_number') <span
                                                    class="login-danger">*</span></label>
                                            <input class="form-control" id="file_number" type="text" placeholder="@lang('admin.file_number')" value="{{old('file_number')}}" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.payment_method') <span
                                                    class="login-danger">*</span></label>
                                            <select class="form-control select" name="payment_method" value="{{old('payment_method')}}">
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

                                    <div class="col-12 col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table border-0 custom-table invoices-table"
                                                   id="servicesTable">
                                                <thead>
                                                <tr>
                                                    <th style="width: 20px">#</th>
                                                    <!-- <th class="col-sm-1">الكود</th> -->
                                                    <th class="col-sm-1">@lang('admin.service_type')</th>
                                                    <th class="col-md-3">@lang('admin.service_name')</th>
                                                    <th class="col-md-4">@lang('admin.description')</th>
                                                    <th style="width:80px;">@lang('admin.qty')</th>
                                                    <th style="width:100px;">@lang('admin.price')</th>
                                                    <th style="width:80px;">@lang('admin.tax')</th>
                                                    <th>@lang('admin.reception.options')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <!-- <td>
                                                        <input class="form-control" placeholder="الكود" type="text" style="min-width:150px">
                                                    </td> -->
                                                    <td>
                                                        <div class="form-group local-forms mb-0" style="min-width:150px">
                                                            <select class="form-control select table-select" name="service_type[]" id="service_type" required>
                                                                <option selected disabled>@lang('admin.service_type')</option>
                                                                <option value="1">@lang('admin.analysis')</option>
                                                                <option value="2">@lang('admin.rays')</option>
                                                                <option value="3">@lang('admin.service')</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select class="form-control select table-select" name="service_name[]" id="service_name" required>
                                                            <option selected disabled>@lang('admin.service_name')</option>
                                                            <option value="1">@lang('admin.analysis')</option>
                                                            <option value="2">@lang('admin.rays')</option>
                                                            <option value="3">@lang('admin.service')</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control"
                                                               placeholder="@lang('admin.description')" type="text"
                                                               name="notes[]"
                                                               style="min-width:150px" >
                                                    </td>
                                                    <td>
                                                        <input class="form-control" placeholder="@lang('admin.qty')"
                                                               style="width:80px" type="number" id="qty" name="qty[]" value="1">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" placeholder="@lang('admin.price')"
                                                               style="width:120px" type="text" id="price" name="price[]" >
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="tax_percentage" id="tax_percentage" value="{{$data['tax']->tax ?? 0}}">
                                                        <input class="form-control" placeholder="@lang('admin.tax')" style="width:100px" type="text" id="tax" name="tax[]"  readonly>
                                                    </td>
                                                    <td class="d-flex gap-1" style="height: 65px;">
                                                        <a href="javascript:void(0)"
                                                           onclick="addNewTableRow('servicesTable')"
                                                           class="text-success font-18 add-table-invoice" title="Add"><i
                                                                class="fa fa-plus"></i></a>
                                                        <a href="javascript:void(0)"
                                                           onclick="deleteTableRow('servicesTable', event)"
                                                           class="text-success font-18 add-table-invoice delete"
                                                           title="Delete"><i class="fa fa-trash-alt"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
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
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label style="font-size: 14px;">@lang('admin.price')</label>
                                                            <input type="text" class="form-control" id="services_price" name="services_price" value="{{old('services_price')}}" readonly required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.discount')</label>
                                                            <input type="number" class="form-control" value="0" id="discount" name="discount" readonly  required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.patient_tax')</label>
                                                            <input type="text" readonly class="form-control" name="patient_tax" id="patient_tax"  value="{{old('patient_tax')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label style="font-size: 14px;">@lang('admin.total')</label>
                                                            <input type="text" class="form-control" name="total" id="services_total" value="{{old('total')}}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.patient_cost')</label>
                                                            <input type="hidden" id="company_percentage" >
                                                            <input type="text" class="form-control" name="patient_cost"  id="patient_cost" value="{{old('patient_cost')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2">
                                                            <label style="font-size: 14px;">@lang('admin.company_cost')</label>
                                                            <input type="text" class="form-control" name="company_cost" id="company_cost" value="{{old('company_cost')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.company_tax')</label>
                                                            <input type="text" class="form-control" name="company_tax" value="0" readonly >
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                style="font-size: 14px;">@lang('admin.patient_total') </label>
                                                            <input type="text" class="form-control" name="patient_total" id="patient_total" value="{{old('patient_total')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
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
                        $('#company_percentage').val(res.patient.company.amount ?? 0);

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
            $('#specialist_id').on('change', function () {
                var specialist_id = this.value;
                $('#doctor_id').html('');
                $.ajax({
                    url: '{{ route('getDoctorsFromSpecialists') }}?specialist_id=' + specialist_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.doctors_count > 0) { // all was ok
                            $('#doctor_id').append('<option value="">{{ trans('admin.select') }}</option>');
                            $.each(res.data, function (key, value) {
                                $('#doctor_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        } else {
                            $('#doctor_id').html('<option value="">@lang('admin.no_data')</option>');
                        }
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
                $('#remaining_amount').val(total_amount - amount_paid);

            });

        });
    </script>
@endsection
