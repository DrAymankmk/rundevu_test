@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .supervisor-form-card .card-body {
            padding: 30px;
        }

        .supervisor-form-grid {
            max-width: 980px;
            margin-left: auto;
            margin-right: 0;
        }

        [dir="ltr"] .supervisor-form-grid {
            margin-left: 0;
            margin-right: auto;
        }

        .supervisor-form-card label {
            color: #344054;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .supervisor-form-card .form-control,
        .supervisor-form-card .input-group-text {
            min-height: 46px;
            border-radius: 8px;
        }

        .supervisor-upload-row {
            display: grid;
            grid-template-columns: 88px minmax(0, 1fr);
            gap: 16px;
            align-items: center;
        }

        .supervisor-upload-preview {
            width: 88px;
            height: 88px;
            border-radius: 8px;
            background: #eef3ff;
            border: 1px solid #dfe7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: #3557a8;
            font-size: 28px;
        }

        .supervisor-upload-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .supervisor-upload-field {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 72px;
            padding: 16px;
            border: 1px dashed #b9c7e8;
            border-radius: 8px;
            background: #f8fbff;
        }

        .supervisor-upload-field .custom-file-input {
            position: absolute;
            opacity: 0;
            width: 1px;
            height: 1px;
            pointer-events: none;
        }

        .supervisor-upload-field .custom-file-label {
            position: static;
            display: inline-flex;
            align-items: center;
            min-height: 38px;
            width: auto;
            margin: 0;
            padding: 8px 14px;
            border: 0;
            border-radius: 6px;
            background: #3557a8;
            color: #fff;
            cursor: pointer;
        }

        .supervisor-upload-field .custom-file-label::after {
            display: none;
        }

        .supervisor-upload-name {
            display: block;
            color: #667085;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .supervisor-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }
    </style>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h5>@lang('admin.edit') ( {{ $supervisor->name }})</h5>
                            <div class="col-md-6">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card supervisor-form-card">
                        <div class="card-body">
                            <form class="needs-validation" novalidate=""
                                  action="{{route('clinic.supervisor.update', $supervisor->id)}}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="modal-body supervisor-form-grid">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom01">@lang('admin.name')</label>
                                            <input class="form-control" id="validationCustom01" type="text"
                                                   placeholder="اسم المشرف" value="{{ $supervisor->name}}"
                                                   name="name"
                                                   required="">
                                            <div class="valid-feedback">Looks good!</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom01">@lang('admin.mobile_number')</label>
                                            <label for="validationCustom02"> </label>
                                            <input class="form-control" id="validationCustom02" type="number"
                                                   placeholder="رقم الهاتف " value="{{ $supervisor->phone }}"
                                                   name="phone"
                                                   required="">
                                            <div class="valid-feedback">Looks good!</div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustomUsername">@lang('admin.email')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"
                                                                                       id="inputGroupPrepend">@</span>
                                                </div>
                                                <input class="form-control" id="validationCustomUsername" type="email"
                                                       placeholder="البريد الالكترونى" value="{{$supervisor->email }}"
                                                       name="email" aria-describedby="inputGroupPrepend"
                                                       required="">
                                                <div class="invalid-feedback">ادخل البريد الالكترونى.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-8 mb-3">
                                            <label for="validationCustom05">@lang('admin.image')</label>
                                            <div class="supervisor-upload-row">
                                                <div class="supervisor-upload-preview" id="supervisorImagePreview">
                                                    <img src="{{$supervisor->image }}" alt="">
                                                </div>
                                            <div class="custom-file supervisor-upload-field">
                                                <input class="custom-file-input" id="validatedCustomFile" type="file"
                                                       name="image"
                                                       accept="image/*">
                                                <label class="custom-file-label" for="validatedCustomFile">اختر
                                                    صوره</label>
                                                <span class="supervisor-upload-name" id="supervisorImageName">{{ app()->getLocale() == 'ar' ? 'لم يتم اختيار صورة جديدة' : 'No new image selected' }}</span>
                                                <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                            </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="supervisor-actions">
                                    <button class="btn btn-primary"
                                            type="submit"> @lang('admin.edit')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>

            <script>
                var input = document.getElementById('validatedCustomFile');
                var preview = document.getElementById('supervisorImagePreview');
                var fileName = document.getElementById('supervisorImageName');

                if (input && preview && fileName) {
                    input.addEventListener('change', function () {
                        var file = input.files && input.files[0];
                        if (!file) {
                            fileName.textContent = "{{ app()->getLocale() == 'ar' ? 'لم يتم اختيار صورة جديدة' : 'No new image selected' }}";
                            return;
                        }

                        fileName.textContent = file.name;
                        var reader = new FileReader();
                        reader.onload = function (event) {
                            preview.innerHTML = '<img src="' + event.target.result + '" alt="">';
                        };
                        reader.readAsDataURL(file);
                    });
                }
            </script>

            <!-- END PAGE CONTENT WRAPPER -->
@endsection
