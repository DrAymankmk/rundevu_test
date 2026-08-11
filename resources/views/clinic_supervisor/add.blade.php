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

        .supervisor-upload {
            display: grid;
            grid-template-columns: 88px minmax(0, 1fr);
            gap: 16px;
            align-items: center;
            padding: 16px;
            border: 1px dashed #b9c7e8;
            border-radius: 8px;
            background: #f8fbff;
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

        .supervisor-upload-preview + .supervisor-upload-field {
            margin-top: -88px;
            margin-inline-start: 104px;
            min-height: 88px;
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

        .supervisor-upload-field .invalid-feedback {
            margin: 0;
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

        .supervisor-upload-file {
            position: absolute;
            opacity: 0;
            width: 1px;
            height: 1px;
            pointer-events: none;
        }

        .supervisor-upload-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 38px;
            padding: 8px 14px;
            margin: 0;
            border-radius: 6px;
            background: #3557a8;
            color: #fff !important;
            cursor: pointer;
        }

        .supervisor-upload-name {
            display: block;
            margin-top: 9px;
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

        @media (max-width: 575.98px) {
            .supervisor-upload-preview + .supervisor-upload-field {
                margin-top: 12px;
                margin-inline-start: 0;
            }
        }
    </style>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h5>@lang('admin.add_supervisor')</h5>
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
                                  action="{{route('clinic.supervisor.store')}}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="modal-body supervisor-form-grid">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom01">@lang('admin.name')</label>
                                            <input class="form-control" id="validationCustom01" type="text"
                                                   placeholder="@lang('admin.name')" value="{{ old('name') }}"
                                                   name="name"
                                                   required="">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom01">@lang('admin.mobile_number')</label>
                                            <label for="validationCustom02"> </label>
                                            <input class="form-control" id="validationCustom02" type="number"
                                                   placeholder="@lang('admin.mobile_number') "
                                                   value="{{ old('phone') }}" name="phone"
                                                   required="">

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
                                                       placeholder="@lang('admin.email')" value="{{ old('email') }}"
                                                       name="email" aria-describedby="inputGroupPrepend"
                                                       required="">
                                                <div class="invalid-feedback">ادخل البريد الالكترونى.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom03">@lang('admin.password')</label>
                                            <input class="form-control" id="validationCustom03" type="password"
                                                   placeholder="@lang('admin.password')" value="{{ old('password') }}"
                                                   name="password"
                                                   required="">
                                            <div class="invalid-feedback">ادخل كلمه المرور.</div>
                                        </div>
                                    </div>

                                    <div class="supervisor-upload-preview" id="supervisorImagePreview">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <div class="custom-file supervisor-upload-field">
                                        <input class="custom-file-input" id="validatedCustomFile" type="file"
                                               required=""
                                               name="image"
                                               accept="image/*">
                                        <label class="custom-file-label"
                                               for="validatedCustomFile">@lang('admin.image')</label>
                                        <span class="supervisor-upload-name" id="supervisorImageName">{{ app()->getLocale() == 'ar' ? 'لم يتم اختيار صورة' : 'No image selected' }}</span>
                                        <div class="invalid-feedback">اختر ملف</div>
                                    </div>
                                    <br>
                                </div>
                                <div class="supervisor-actions">
                                    <button class="btn btn-primary"
                                            type="submit">@lang('admin.add')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('validatedCustomFile');
            var preview = document.getElementById('supervisorImagePreview');
            var fileName = document.getElementById('supervisorImageName');

            if (!input || !preview || !fileName) {
                return;
            }

            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) {
                    fileName.textContent = "{{ app()->getLocale() == 'ar' ? 'لم يتم اختيار صورة' : 'No image selected' }}";
                    preview.innerHTML = '<i class="fa fa-user"></i>';
                    return;
                }

                fileName.textContent = file.name;
                var reader = new FileReader();
                reader.onload = function (event) {
                    preview.innerHTML = '<img src="' + event.target.result + '" alt="">';
                };
                reader.readAsDataURL(file);
            });
        });
    </script>

    <!-- END PAGE CONTENT WRAPPER -->
@endsection
