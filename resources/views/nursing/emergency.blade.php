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

                                <a href="{{ route('new-patient.newPatient') }}" class="btn btn-primary btn-rounded"><i
                                        class="fa fa-plus m-r-5"></i> @lang('admin.new_patient')</i></a>

                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-loader">
                                <div class="spinner"></div>
                            </div>
                            <form action="{{ route('store-emergency.store') }}" method="POST">
                                @csrf
                                <div class="settings-form row">
                                    <div class="form-group col-12 col-md-6">
                                        <label class="mb-1" for="patient">@lang('admin.Patient name')</label>
                                        @error('user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <select id="patient" class="form-control setting-input" style="width: 100%;"
                                            name="user_id">
                                            <option disabled selected>@lang('admin.select patient name')</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" @if(old('user_id') == $user->id) selected @endif
                                                    data-file-number="{{ $user->file_number }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label class="mb-1">@lang('admin.patient_file')</label>
                                        <input type="text" class="form-control setting-input" id="file-number"
                                            placeholder="@lang('admin.patient_file')" readonly>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.temperature') <span class="star-red">*</span></label>
                                        @error('heat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="heat" value="{{ old('heat') }}"
                                            placeholder="@lang('admin.temperature')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">Cْ</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.pulse') <span class="star-red">*</span></label>
                                        @error('pulse')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="pulse" value="{{ old('pulse') }}"
                                            placeholder="@lang('admin.pulse')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">BPM</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.blood_pressure') <span class="star-red">*</span></label>
                                        @error('blood_pressure')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="blood_pressure" value="{{ old('blood_pressure') }}"
                                            placeholder="@lang('admin.blood_pressure')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">MMHG</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.pain_rate') <span class="star-red">*</span></label>
                                        @error('pain_rate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="pain_rate" value="{{ old('pain_rate') }}"
                                            placeholder="@lang('admin.pain_rate')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">DL</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.weight') <span class="star-red">*</span></label>
                                        @error('weight')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="weight" value="{{ old('weight') }}"
                                            placeholder="@lang('admin.weight')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">KG</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.height') <span class="star-red">*</span></label>
                                        @error('height')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="height" value="{{ old('height') }}"
                                            placeholder="@lang('admin.height')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">CM</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.breathing') <span class="star-red">*</span></label>
                                        @error('breathing')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="breathing" value="{{ old('breathing') }}"
                                            placeholder="@lang('admin.breathing')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">FVC</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.oxygen_ratio') <span class="star-red">*</span></label>
                                        @error('oxygen_ratio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="oxygen_ratio" value="{{ old('oxygen_ratio') }}"
                                            placeholder="@lang('admin.oxygen_ratio')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">SPO2</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">@lang('admin.body_mass_rate') <span class="star-red">*</span></label>
                                        @error('body_mass_rate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="body_mass_rate"  value="{{ old('body_mass_rate') }}"
                                            placeholder="@lang('admin.body_mass_rate')">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text"><sup>2</sup>KG/M</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">FBS <span class="star-red">*</span></label>
                                        @error('FBS')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="FBS" value="{{ old('FBS') }}"
                                            placeholder="FBS">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">N/A</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 position-relative">
                                        <label class="mb-1">RBS <span class="star-red">*</span></label>
                                        @error('RBS')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <input type="text" class="form-control setting-input" name="RBS"  value="{{ old('RBS') }}"
                                            placeholder="RBS">
                                        <div class="input-group-prepend vital-icon">
                                            <span class="input-group-text">N/A</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label class="mb-1">@lang('admin.drug')</label>
                                        <select multiple id="drugs" class="form-control setting-input"
                                            name="drugs_id[]" style="width: 100%;">

                                            @foreach ($drugs as $item)
                                                <option value="{{ $item->id }}" @if(in_array($item->id, old('drugs_id', []))) selected @endif>
                                                    @if (app()->getLocale() == 'en')
                                                        {{ $item->name_en }}
                                                    @else
                                                        {{ $item->name }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="form-group col-12">
                                        <label class="mb-1" for="doctors">@lang('admin.doctors')</label>
                                        <select multiple id="doctors" class="form-control setting-input"
                                            name="doctor_id[]" style="width: 100%;">

                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" @if(in_array($doctor->id, old('doctor_id', []))) selected @endif
                                                    >{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12">
                                        <label class="mb-1" for="nurses">@lang('admin.Nurses') </label>
                                        <select multiple id="nurses" class="form-control setting-input"
                                            name="nurse_id[]" style="width: 100%;">

                                            @foreach ($nurses as $nurse)
                                                <option value="{{ $nurse->id }}" @if(in_array($nurse->id, old('nurse_id', []))) selected @endif>{{ $nurse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-12">
                                        <label class="mb-1" for="note">@lang('admin.notes')</label>
                                        <textarea id="note" class="form-control setting-input" name="notes" placeholder="@lang('admin.notes')"
                                            rows="3" cols="30">{{ old('notes') }}</textarea>
                                    </div>
                                    <div class="form-group mb-0 mt-4">
                                        <div class="settings-btns text-end">

                                            <div id="update">
                                                <button type="submit"
                                                    class="border-0 btn btn-primary btn-gradient-primary btn-rounded">@lang('admin.save')</button>&nbsp;&nbsp;
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
            var selectedUserId = $('#patient').val();
            var selectedFileNumber = $('#patient').find(':selected').data('file-number');
                // Update the file number input field
            $('#file-number').val(selectedFileNumber);
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

        function updateForm(type) {
            if (type === 'cancel') {
                // Redirect to the desired route
                window.location.href = '{{ route('emergency.index') }}';

            }

        }
    </script>
@endsection
