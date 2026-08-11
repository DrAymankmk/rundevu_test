@extends('includes_admin.mainlayout')
@section('content')
    <style>
        .col-form-label {
            font-family: 'Tajawal', serif;
            font-weight: bold;
            background-color: #2E40AE;
            color: #fff;
        }

        .col-form {
            font-family: 'Tajawal', serif;
            font-weight: bold;
            background-color: #000;
            color: #000;
            border-color: #000;
        }
    </style>

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i><a
                                    href="{{ route('patient-file',$reservation->id) }}">@lang('admin.patient_file_clinic') </a>
                            </li>
                            <li class="breadcrumb-item active">@lang('admin.appointment_booking')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="card-title">@lang('admin.patient_data')</h4>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.patient_name')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly
                                           value="{{ $reservation->user->name ??null }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.patient_file')</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly
                                           value="{{ $reservation->user->file_number ??null }}">
                                </div>
                            </div>

                            <hr class="col-form">
                            <hr class="col-form">

                    </div>
                </div>


                <div class="col-sm-6">
                    <div class="card-box">
                        <h4 class="card-title">@lang('admin.appointment_booking')</h4>
                        <form action="{{ route('create_schedule_consultation', $reservation->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{$reservation->doctor_id}}">
                            <input type="hidden" name="type" value="2">
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">@lang('admin.date')</label>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" name="date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('date') }}" id="datepicker">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div id="availableTimes"></div>
                            </div>

                            <div class="form-group text-center" id="schedule_consultation" style="display: none">
                                <button class="btn btn-primary"
                                        type="submit">@lang('admin.appointment_booking')</button>
                            </div>
                        </form>

                        @if($new_reservation)
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">@lang('admin.date')</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="schedule_consultation_date"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       value="{{$new_reservation->date ?? null}}" id="datepicker">
                            </div>

                            <label class="col-form-label col-md-2">@lang('admin.time')</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" readonly name="schedule_consultation_date"
                                       value="{{$new_reservation->appointment ?? null}}" id="datepicker">
                            </div>




                                <a class="btn btn-sm  text-danger text-center" href="#" data-bs-toggle="modal"
                                   data-bs-target="#delete_reservation{{$new_reservation->id}}" style="margin-top: 20px"><i
                                        class="fa fa-trash-alt m-r-5"></i> @lang('admin.delete')
                                </a>
                                    <div id="delete_reservation{{$new_reservation->id}}" class="modal fade delete-modal"
                                     role="dialog">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form class="needs-validation" novalidate=""
                                                  action="{{route('destroyReservation',$new_reservation->id) }}"
                                                  method="POST">
                                                {{ method_field('delete') }}
                                                {{ csrf_field() }}
                                                <div class="modal-body text-center">
                                                    <img src="/assets/img/sent.png" alt="" width="50"
                                                         height="46">
                                                    <h3>@lang('admin.confirm_delete')</h3>
                                                    <div class="m-t-20"><a href="#" class="btn btn-white"
                                                                           data-bs-dismiss="modal">@lang('admin.close')</a>
                                                        <button type="submit"
                                                                class="btn btn-danger">@lang('admin.delete')</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                        </div>
                        @endif

                    </div>



                </div>

            </div>
        </div>
    </div>
    <script src="/assets/js/jquery-3.6.1.min.js"></script>
    <script>
        $(document).ready(function ($) {
        $('#datepicker').change(function () {
            var selectedDate = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('get.available.times') }}',
                data: {
                    date: selectedDate
                },
                success: function (data) {
                    // Handle the returned data (available times)

                    if (data === 0) {
                        $('#schedule_consultation').hide();
                        $('#availableTimes').html('<span style="color:#f00;text-align:center;font-weight:bold">{{ trans("admin.no_available_appointment") }}</span>');
                        return true;
                    }

                    // Update your UI with the available times
                    var availableTimesHtml = '';
                    $.each(data, function (index, time) {
                        var available = 'checked';
                        var color = '#06BA99';
                        if (time.status == 1) {
                            available = 'checked';
                            color = '#06BA99';
                        } else {
                            available = 'disabled';
                            color = '#f00';
                        }
                        availableTimesHtml += '<div class="form-check form-check-inline col-md-4" style="background-color: ' + color + ';margin-bottom: 1.125rem ">' +
                            '<input class="form-check-input" type="radio" name="appointment" id="time_' + index + '" value="' + time.time + '" ' + available + '>' +
                            '<label class="form-check-label" for="time_' + index + '">' +
                            time.time +
                            '</label>' +
                            '</div>';
                    });
                    $('#availableTimes').html(availableTimesHtml);
                    $('#schedule_consultation').show();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
        });
    </script>
@endsection
