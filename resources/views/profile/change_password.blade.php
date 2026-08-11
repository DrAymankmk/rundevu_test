@extends('includes_admin.mainlayout')
@section('content')
    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i>{{ trans('admin.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('admin.change_password') }}</li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="needs-validation" novalidate=""
                                  action="{{ route('add-password') }}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="modal-body">

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="old_password">{{ trans('admin.old_password') }}</label>
                                            <input class="form-control" type="password" name="old_password"
                                                   id="old_password"
                                                   required
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.old_password') }}">
                                            <div class="invalid-feedback">{{ $errors->first('old_password') }}.</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="new_password">{{ trans('admin.new_password') }}</label>
                                            <input class="form-control" type="password" name="new_password"
                                                   id="new_password"
                                                   required
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.new_password') }}">
                                            <div class="invalid-feedback">{{ $errors->first('new_password') }}.</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="confirm_password">{{ trans('admin.confirm_password') }}</label>
                                            <input class="form-control" type="password" name="confirm_password"
                                                   id="confirm_password"
                                                   required
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.confirm_password') }}">
                                            <div class="invalid-feedback">{{ $errors->first('confirm_password') }}.
                                            </div>
                                            <span id='message'></span>
                                        </div>
                                        <input type="hidden" name="lang" id="lang" value="{{app()->getLocale()}}"/>


                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <button class="btn btn-primary"
                                            type="submit">{{ trans('admin.save') }}
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>--}}
{{--    <script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>--}}

    <script>
        // $(document).ready(function () {
        //
        //     var lang = document.getElementById("lang");
        //     var password = document.getElementById("new_password");
        //     var confirm_password = document.getElementById("confirm_password");
        //
        //     function validatePassword() {
        //         alert(password.value);
        //         alert(confirm_password.value);
        //         if (password.value != confirm_password.value) {
        //             if (lang.value == 'en') {
        //                 confirm_password.setCustomValidity('Passwords do not match');
        //             } else {
        //                 confirm_password.setCustomValidity('كلمة المرور الجديدة  غير متطابقة مع تاكيد كلمة المرور');
        //             }
        //             confirm_password.reportValidity();
        //         } else if (password.value.trim() == "") {
        //             password.setCustomValidity("Passwords must not be empty");
        //             password.reportValidity();
        //         } else {
        //             password.setCustomValidity("");
        //         }
        //     }
        //
        //     password.onchange = validatePassword;
        //     confirm_password.onkeyup = validatePassword;
        //
        // });
        // $('#password, #confirm_password').on('keyup', function () {
        //     if ($('#new_password').val() == $('#confirm_password').val()) {
        //         $('#message').html('Matching').css('color', 'green');
        //     } else
        //         $('#message').html('Not Matching').css('color', 'red');
        // });
    </script>

@endsection
