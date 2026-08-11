<?php $page = 'notificationsList.create'; ?>
@extends('layout_new.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- row start -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- page header start -->
                    <div class="mb-4">
                        <h6 class="fs-14 fw-semibold mb-0 d-flex align-items-center"> <a href="{{route('notificationsList.index')}}" class="text-dark"> <i class="ti ti-chevron-left me-1"></i>@lang('admin.Notifications')</a></h6>
                    </div>
                    <!-- page header end -->

                    <!-- card start -->
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('notificationsList.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">@lang('main.title_en')<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="title_en" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">@lang('main.title_ar')<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="title_ar" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">@lang('main.sender_type')<span class="text-danger ms-1">*</span></label>
                                            <select class="form-control"  id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select Type" name="choices-single-groups">
                                                <option value="">@lang('main.all')</option>
                                                <optgroup label="@lang('main.all_types_apps')">
                                                    <option value="0">@lang('main.all')</option>
                                                    <option value="00">@lang('main.clinics')</option>
                                                    <option value="000">@lang('main.all_users')</option>
                                                </optgroup>
                                                <optgroup label="@lang('main.packages')">
                                                    @foreach($packages as $package)
                                                        <option value="{{$package->id}}">{{ $package->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="@lang('main.clinics')" id="clinics-options"></optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">@lang('main.image')</label>
                                            <input class="form-control" type="file" id="formFile" name="image">
                                        </div>
                                    </div>



                                    <div class="col-lg-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 text-dark fs-14 fw-medium">@lang('main.message_en') <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <textarea class="form-control" rows="4" name="message_en" required></textarea>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->

                                    <div class="col-lg-6 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 text-dark fs-14 fw-medium">@lang('main.message_ar') <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <textarea class="form-control" rows="4" name="message_ar" required></textarea>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->


                                    <div class="col-md-6" style="display: none">
                                        <div class="mb-3">
                                            <label class="form-label mb-1 fw-medium">@lang('main.clinics')<span class="text-danger ms-1">*</span></label>
                                            <select class="form-control" id="choices-single-clinic" name="clinic_id">
                                                <!-- Filled dynamically -->
                                            </select>
                                        </div>
                                    </div>


                                </div>

                                <div class="d-flex align-items-center justify-content-end">
                                    <a href="javascript:void(0);" class="btn btn-light me-2">@lang('main.Cancel')</a>
                                    <button type="submit" class="btn btn-primary">@lang('main.Add Notification')</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- card end -->



                </div>
            </div>
            <!-- row end -->

        </div>
        <!-- End Content -->

    </div>

    <!-- ========================
        End Page Content
    ========================= -->
    <script src="{{ asset('/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            const mainSelect = document.getElementById('choices-single-groups');
            const clinicSelect = document.getElementById('choices-single-clinic');

            const mainChoices = new Choices(mainSelect, {
                shouldSort: false
            });

            const clinicChoices = new Choices(clinicSelect, {
                shouldSort: false
            });

            $('#choices-single-groups').on('change', function () {
                const selected = $(this).val();

                if (selected === '00') {
                    // Show the clinic select field
                    $('#choices-single-clinic').closest('.col-md-6').show();

                    // Load clinics via AJAX
                    $.ajax({
                        url: '{{ route("get-clinics") }}',
                        type: 'GET',
                        success: function (response) {
                            // Clear existing clinic choices
                            clinicChoices.clearChoices();

                            // Add new clinics
                            const newClinicChoices = response.map(clinic => ({
                                value: clinic.id,
                                label: clinic.name
                            }));

                            clinicChoices.setChoices(newClinicChoices, 'value', 'label', true);
                        },
                        error: function () {
                            alert('Failed to load clinics.');
                        }
                    });
                } else {
                    // Hide the clinic select field
                    $('#choices-single-clinic').closest('.col-md-6').hide();

                    // Clear previous selection
                    clinicChoices.clearChoices();
                }
            });
        });
    </script>



@endsection
