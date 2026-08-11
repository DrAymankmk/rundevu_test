@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('save-patient.savePatient') }}" enctype="multipart/form-data"
                                class="" class="was-validated needs-validation">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-heading">
                                            <h4>@lang('admin.reception.Patient data') </h4>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.reception.name') <span class="login-danger">*</span></label>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input class="form-control" type="text" placeholder="@lang('admin.reception.name')"
                                                name="name" value="{{ old('name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.reception.Father Name') <span class="login-danger">*</span></label>
                                            @error('father_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input class="form-control" type="text" placeholder="@lang('admin.reception.Father Name')"
                                                name="father_name" value="{{ old('father_name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.reception.Grandfather name') <span class="login-danger">*</span></label>
                                            @error('Grandfather_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input class="form-control" type="text" placeholder="@lang('admin.reception.Grandfather name')"
                                                name="Grandfather_name" value="{{ old('Grandfather_name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.reception.Family name') <span class="login-danger">*</span></label>
                                            @error('family_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input class="form-control" type="text" placeholder="@lang('admin.reception.Family name')"
                                                name="family_name" value="{{ old('family_name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label> @lang('admin.phone')<span class="login-danger">*</span></label>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input class="form-control" type="text" name="phone"
                                                placeholder="@lang('admin.phone')" value="{{ old('phone') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-6">
                                        <div class="form-group local-forms">
                                            <label>@lang('admin.address')<span class="login-danger">*</span></label>
                                            @error('address_1')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input class="form-control" type="text" name="address_1"
                                                placeholder="@lang('admin.address')" value="{{ old('address_1') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label for="city_id"> @lang('admin.reception.city')<span
                                                    class="login-danger">*</span></label>
                                            @error('city_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <select class="form-control select" id="city_id" name="city_id" required>
                                                <option selected="true" disabled="disabled">@lang('admin.reception.select_city') </option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"  @if(old('city_id') == $city->id) selected @endif>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="form-group local-forms">
                                            <label for="gender"> @lang('admin.gender')<span
                                                    class="login-danger">*</span></label>
                                            @error('gender')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <select class="form-control select" name="gender" >
                                                <option selected="true" disabled="disabled">@lang('admin.select')
                                                    @lang('admin.gender')</option>
                                                <option value="1"  @if(old('gender') == 1) selected @endif>@lang('admin.male')</option>
                                                <option value="2"  @if(old('gender') == 2) selected @endif>@lang('admin.female')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ Session()->get('lang') }}" name="lang"
                                        id="lang">
                                    <div class="col-12">
                                        <div class="doctor-submit text-end">
                                            <button type="submit" id="add_patient"
                                                class="btn btn-primary submit-form me-2">@lang('admin.save')</button>
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
@endsection
