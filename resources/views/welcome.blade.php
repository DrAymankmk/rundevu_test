<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="icon" href="{{ asset('images/logo/logo.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/logo/logo.png')}}" type="image/x-icon">
    <title>تسجيل عيادة</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    {{--    <link--}}
    {{--        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css"--}}
    {{--        rel="stylesheet">--}}
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/fontawesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/feather-icon.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/select2.css') }}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/timepicker.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/rating.css') }}">

    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/date-picker.css')}}">

    {{--    <!-- Plugins css start-->--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/datatables.css')}}">


    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/prism.css')}}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/style.css')}}">

    <link id="color" rel="stylesheet" href="{{asset('/admin/css/light-1.css')}}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/css/responsive.css')}}">
    <style>
        .authentication-main {
            background-image: url("{{ asset('media/logo/logo.jpg') }}");
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>

</head>

<body main-theme-layout="rtl">
<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="loader bg-white">
        <div class="whirly-loader"></div>
    </div>
</div>

<div class="page-body">
    <!-- Default ordering (sorting) Starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    @if ($message = \Illuminate\Support\Facades\Session::get('message'))
                        <div class="alert alert-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <br><br>
                    <div class="text-center">
                        <h6 class="textshadow" style="font-size:20px;font-weight: bold">تسجيل حساب عيادة</h6>
                    </div>
                    <div class="authentication-main text-center">
                        <img style="width: 30%;"
                             src="{{ asset('media/logo/logo.jpg')}}" alt="">
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate="" action="{{route('register')}}"
                              method="POST" enctype="multipart/form-data" autocomplete="off">
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="form-group">
                                    <div id="shoplocation" style="width:100%;height:350px"></div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom05">تخصصات العيادة</label>
                                        <select id="specialty_id" class="js-example-placeholder-multiple form-control"
                                                name="specialty_id[]" multiple required>
                                            {{--                                            <option value="" disabled selected>اختر</option>--}}
                                            @foreach($specialties as $specialty)
                                                <option value="{{$specialty->id}}">{{$specialty->name_ar}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger page-header-left">{{ $errors->first('type') }}</div>
                                    </div>
                                    <input type="hidden" name="register" value="1">
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom05"> المدن</label>
                                        <select id="city_id" class="js-example-placeholder-multiple form-control"
                                                name="city_id"  required>
                                            <option value="" disabled selected>اختر</option>
                                            @foreach($cities as $city)
                                                <option value="{{$city->id}}">{{$city->name_ar}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger page-header-left">{{ $errors->first('city_id') }}</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom04">اسم العيادة</label>
                                        <input class="form-control" type="text" name="name"
                                               placeholder="ادخل الاسم" value="{{old('name')}}"
                                               required>
                                        <div class="text-danger page-header-left">{{ $errors->first('name') }}.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom04">رقم الهاتف</label>
                                        <input class="form-control" type="number" name="phone"
                                               placeholder="ادخل رقم الهاتف" value="{{old('phone')}}"
                                               required>
                                        <div class="text-danger page-header-left">{{ $errors->first('phone') }}.</div>
                                    </div>

                                </div>
                                <div class="form-row">

                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom04">البريد الالكترونى</label>
                                        <input class="form-control" type="email" name="email"
                                               placeholder="ادخل البريد الالكترونى"
                                               value="{{old('email')}}"
                                               required>
                                        <div class="text-danger page-header-left">{{ $errors->first('email') }}.</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom04">كلمه المرور</label>
                                        <input class="form-control" type="password" name="password"
                                               placeholder="ادخل كلمه المرور" required>
                                        <div class="text-danger page-header-left">{{ $errors->first('password') }}.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom05">العنوان</label>
                                        <input type="text" class="form-control" name="address"
                                               value="{{old('address')}}"
                                               id="autocomplete"
                                               placeholder="بحث عن مكان" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="custom-file">
                                            <label for="validationCustom05">صوره</label>
                                            <input class="form-control" type="file" name="image" required>
                                        </div>
                                        <div
                                            class="text-danger page-header-left">{{ $errors->first('image') }}
                                        </div>
                                    </div>

                                </div>


                                <div class="form-row">

                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom04">وصف عن العيادة</label>
                                        <textarea class="form-control"
                                                  name="desc" required>{{old('desc')}}</textarea>
                                        <div
                                            class="text-danger page-header-left">{{ $errors->first('desc') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3" style="display: none">
                                        <label for="validationCustom04">Latitude</label>
                                        <input type="text" required class="form-control" name="lat"
                                               value="{{old('lat')}}"
                                               id="lat" placeholder="ادخل موقع العيادة">
                                    </div>
                                    <div class="col-md-6 mb-3" style="display: none">
                                        <label for="validationCustom04">longitude</label>
                                        <input type="text" required class="form-control" name="lng"
                                               value="{{old('lng')}}"
                                               id="lng" placeholder="ادخل موقع العيادة">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button class="btn btn-primary"
                                        type="submit">تسجيل
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer start-->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 footer-copyright">
                        <p class="mb-0" style="font-weight: bold">جميع حقوق النشر محفوظة {{ date('Y') }}</p>
                    </div>

                </div>
            </div>
        </footer>

    </div>
</div>

@include('includes.footer')


@if (session('success'))

    <script>
        new Noty({
            type: 'success',
            'icon': 'check',
            layout: 'bottomRight',
            text: "{{ session('success') }}",
            timeout: 4000,
            killer: true
        }).show();
    </script>
@endif

@if (session('failed'))
    <script>
        $(document).ready(function () {
            new Noty({
                type: 'error',
                layout: 'bottomRight',
                text: "{{ session('failed') }}",
                timeout: 2000,
                killer: true
            }).show();
        });
    </script>

@endif

<script>
    var marker = null;
    var placeSearch, autocomplete;

    function initMap() {
        autocomplete =
            new google.maps.places.Autocomplete((document.getElementById('autocomplete')),
                {types: ['geocode']});
        var map = new google.maps.Map(document.getElementById('shoplocation'), {
            zoom: 10,
            center: {
                lat: 21.485811,
                lng: 39.19250479999999
            }
        });
        var MaekerPos = new google.maps.LatLng(0, 0);
        marker = new google.maps.Marker({
            position: MaekerPos,
            map: map
        });
        autocomplete.addListener('place_changed', function () {
            placeMarkerAndPanTo(autocomplete.getPlace().geometry.location, map);
            document.getElementById("lat").value = autocomplete.getPlace().geometry.location.lat();
            document.getElementById("lng").value = autocomplete.getPlace().geometry.location.lng();
        });
        map.addListener('click', function (e) {
            placeMarkerAndPanTo(e.latLng, map);
            document.getElementById("lat").value = e.latLng.lat();
            document.getElementById("lng").value = e.latLng.lng();
        });
    }

    function placeMarkerAndPanTo(latLng, map) {
        map.setZoom(9);
        marker.setPosition(latLng);
        map.panTo(latLng);
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPf96eskAPXvkyDLPyYhxSCAKIziCUG_E&libraries=places&callback=initMap">
</script>

</body>
</html>
