@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('nursing-requests.index') }}">@lang('admin.Nursing')</a>
                            </li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.vital_signs')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="doctor-table-blk">
                                <h3>@lang('admin.vital_signs')</h3>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-loader">
                                <div class="spinner"></div>
                                {{-- {{ route('data.update', $data->id) }} --}}
                            </div>
                            <form action="{{ route('vital-signs-update.vitalSignsUpdate', $id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                               
                                <div class="settings-form row">
                                    <div class="form-group col-12 col-md-6">
                                        <label class="mb-1" for="patient">@lang('admin.Patient name')</label>
                                        <input type="text" class="form-control setting-input" style="width: 100%;"
                                            value="{{ $vitalSignsData->user->name ?? $nonVitalSignsData->user->name}}" readonly>
                                        <input type="hidden" class="form-control setting-input" style="width: 100%;"
                                            name="user_id" value="{{ $vitalSignsData->user->id ?? $nonVitalSignsData->user->id}}">
                                        {{-- <select id="patient" class="form-control setting-input" style="width: 100%;" name="user_id">
                                            <option disabled selected>اختر اسم مريض</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    vitalSignsData-file-number="{{ $user->file_number }}" >{{ $user->name }}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label class="mb-1">@lang('admin.patient_file')</label>
                                        <input type="text" class="form-control setting-input"
                                            value="{{ $vitalSignsData->user->file_number ?? $nonVitalSignsData->user->file_number  }}" placeholder="@lang('admin.patient_file')"
                                            readonly>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.temperature') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="heat"
                                            value="{{ $vitalSignsData->heat ?? null}}" placeholder="@lang('admin.temperature')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">Cْ</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.pulse') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="pulse"
                                            value="{{ $vitalSignsData->pulse ?? null }}" placeholder="@lang('admin.pulse')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">BPM</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.blood_pressure') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="blood_pressure"
                                            value="{{ $vitalSignsData->blood_pressure ?? null }}" placeholder="@lang('admin.blood_pressure')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">MMHG</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.pain_rate') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="pain_rate"
                                            value="{{ $vitalSignsData->pain_rate ?? null }}" placeholder="@lang('admin.pain_rate')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">DL</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.weight') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="weight"
                                            value="{{ $vitalSignsData->weight ?? null }}" placeholder="@lang('admin.weight')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">KG</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.height') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="height"
                                            value="{{ $vitalSignsData->height  ?? null }}" placeholder="@lang('admin.height')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">CM</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.breathing') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="breathing"
                                            value="{{ $vitalSignsData->breathing  ?? null }}" placeholder="@lang('admin.breathing')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">FVC</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.oxygen_ratio') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="oxygen_ratio"
                                            value="{{ $vitalSignsData->oxygen_ratio ?? null }}" placeholder="@lang('admin.oxygen_ratio')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">SPO2</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.body_mass_rate') <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="body_mass_rate"
                                            value="{{ $vitalSignsData->body_mass_rate ?? null }}" placeholder="@lang('admin.body_mass_rate')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text"><sup>2</sup>KG/M</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">FBS <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="FBS"
                                            value="{{ $vitalSignsData->FBS ?? null }}" placeholder="FBS">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">N/A</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">RBS <span class="star-red">*</span></label>
                                        <input type="text" class="form-control setting-input" name="RBS"
                                            value="{{ $vitalSignsData->RBS ?? null }}" placeholder="RBS">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">N/A</span>
                                        </div>
                                    </div>
                                   
                                    {{-- <div class="form-group col-12">
                                        <label class="mb-1">@lang('admin.drug')</label>
                                     
                                        <select multiple id="drugs" class="form-control setting-input" name="drugs_id[]" style="width: 100%;">
                                            @foreach ($drugggggd as $item)
                                                <option value="{{ $item->drugs->id }}">
                                                    @if (app()->getLocale() == 'en')
                                                        {{ $item->drugs->name_en }}
                                                    @else
                                                        {{ $item->drugs->name }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="form-group col-12">
                                        <label class="mb-1">@lang('admin.drug')</label>
                                    
                                        {{-- Assuming $drugggggd is a collection of ReservationDrug models --}}
                                        <input type="text" class="form-control setting-input" readonly value="{{ $drugs->implode('drugs.name_en', ', ') }}">
                                        {{-- Change 'drugs.name_en' to 'drugs.name' if the English name is not available --}}
                                    </div>
                                    <div class="form-group col-12">
                                        <label class="mb-1" for="doctors">@lang('admin.doctors')</label>
                                        <input type="text" class="form-control setting-input" style="width: 100%;"
                                            value="{{ $vitalSignsData->doctor->name  ?? $nonVitalSignsData->doctor->name }}" readonly>
                                        <input type="hidden" class="form-control setting-input" style="width: 100%;"
                                            name="doctor_id" value="{{ $vitalSignsData->doctor->id ?? $nonVitalSignsData->doctor->id }}">

                                        {{-- <select multiple id="doctors" class="form-control setting-input"
                                            name="doctor_id[]" style="width: 100%;">

                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}"
                                                    {{ $vitalSignsData->doctors->contains($doctor->id) ? 'selected' : '' }}>
                                                    {{ $doctor->name }}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                    {{-- <div class="form-group col-12">
                                        <label class="mb-1" for="nurses">@lang('admin.Nurses') </label>
                                        <select multiple id="nurses" class="form-control setting-input"
                                            name="nurse_id[]" style="width: 100%;">

                                            @foreach ($nurses as $nurse)
                                                <option value="{{ $nurse->id }}"
                                                    {{ $vitalSignsData->nurses->contains($nurse->id) ? 'selected' : '' }}>
                                                    {{ $nurse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="form-group col-12">
                                        <label class="mb-1" for="note">@lang('admin.notes')</label>
                                        <textarea id="note" class="form-control setting-input" name="notes" placeholder="@lang('admin.notes')"
                                            rows="3" cols="30">{{ $vitalSignsData->notes ?? null }}</textarea>
                                    </div>
                                    <div class="form-group mb-0 mt-4">
                                        <div class="settings-btns text-end">
                                            <div id="update">
                                                <button type="submit"
                                                    class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.edit')</button>&nbsp;&nbsp;
                                                <button onclick="updateForm('cancel')" type="button"
                                                    class="btn btn-secondary btn-rounded">@lang('admin.cancel')</button>
                                            </div>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="/assets/js/jquery.js"></script>
    <!-- Select2 -->
    <script src="/assets/js/select2.js"></script>
    <script>
        $(document).ready(function() {
            $('#patient').select2();
            $('#patient').on('change', function() {
                var selectedUserId = $(this).val();
                var selectedFileNumber = $(this).find(':selected').data('file-number');

                // Update the file number input field
                $('#file-number').val(selectedFileNumber);
            });
        });
    </script>
    <script>
        $("#drugs").select2();
        $("#doctors").select2();
        $("#nurses").select2();

        function editForm() {
            let disabledInputs = document.querySelectorAll("[disabled]");
            for (let i = 0; i < disabledInputs.length; i++) {
                disabledInputs[i].removeAttribute("disabled");
            }
            document.getElementById('edit').classList.add('d-none')
            document.getElementById('update').classList.remove('d-none')
        }

        // function updateForm(type) {
        //     if (type === 'update') {
        //         document.getElementsByClassName('table-loader')->tyle.splay = 'flex';
        //         setTimeout(function() {
        //             document.getElementsByClassName('table-loader')->tyle.splay = 'none';
        //         }, 3000)
        //     }
        //     let inputs = document.querySelectorAll(".setting-input");
        //     for (let i = 0; i < inputs.length; i++) {
        //         inputs[i].setAttribute("disabled", true);
        //     }
        //     document.getElementById('edit').classList.remove('d-none')
        //     document.getElementById('update').classList.add('d-none')
        // }
        function updateForm(type) {
            if (type === 'cancel') {
                // Redirect to the desired route
                window.location.href = '{{ route('nursing-requests.index') }}';

            }

        }
    </script>
@endsection
