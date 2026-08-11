@extends('includes_admin.mainlayout')
@section('content')
    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>بيانات اتصل بنا</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> {{trans('admin.dashboard')}} </a></li>
                                <li class="breadcrumb-item active">اتصل بنا

                                </li>
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

                            <form class="needs-validation" novalidate="" action="{{route('edit-contact',$contact->id)}}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div id="shoplocation" style="width:100%;height:350px"></div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom04">{{trans('admin.accounts.email')}}</label>
                                            <input class="form-control" type="email" name="mail"
                                                   placeholder="{{trans('admin.enter')}} {{ trans('admin.accounts.email') }}"
                                                   value="{{!empty($contact->mail) ? $contact->mail  : ""}}"
                                                   required>
                                            <div class="invalid-feedback">{{ $errors->first('email') }}.</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom04">{{trans('admin.accounts.phone')}}</label>
                                            <input class="form-control" type="tel" name="phone"
                                                   placeholder="{{trans('admin.enter')}} {{ trans('admin.accounts.phone') }}"
                                                   value="{{!empty($contact->phone) ? $contact->phone  : ""}}"
                                                   required>
                                            <div class="invalid-feedback">{{ $errors->first('phone') }}.</div>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom05">{{trans('admin.accounts.address')}}</label>
                                            <input type="text" class="form-control" name="address"
                                                   value="{{!empty($contact->address) ? $contact->address  : ""}}"
                                                   id="autocomplete"
                                                   placeholder="{{trans('admin.search')}}" required>
                                        </div>


                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom04">رقم جوال اخر</label>
                                            <input class="form-control" type="tel" name="skype" 
                                                   placeholder="ادخل رقم هاتف ثانى ان وجد"
                                                   value="{{!empty($contact->skype) ? $contact->skype  : ""}}">
                                            <div class="invalid-feedback">{{ $errors->first('skype') }}.</div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3" style="display: none">
                                            <label for="validationCustom04">Latitude</label>
                                            <input type="text" required class="form-control" name="lat"
                                                   value="{{!empty($contact->lat) ? $contact->lat  : ""}}"
                                                   id="lat" placeholder="Enter lat ">
                                        </div>
                                        <div class="col-md-6 mb-3" style="display: none">
                                            <label for="validationCustom04">longitude</label>
                                            <input type="text" required class="form-control" name="lng"
                                                   value="{{!empty($contact->lng) ? $contact->lng  : ""}}"
                                                   id="lng" placeholder="Enter long">
                                        </div>
                                    </div>
                                </div>

                                @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                                    <div class="col-md-4 mb-3">
                                        <button class="btn btn-primary"
                                                type="submit">تعديل البيانات
                                        </button>
                                    </div>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var marker = null;
        var placeSearch, autocomplete;

        function initMap() {
            autocomplete =
                new google.maps.places.Autocomplete((document.getElementById('autocomplete')),
                );
            var map = new google.maps.Map(document.getElementById('shoplocation'), {
                zoom: 10,
                center: {
                    lat: {{$contact->lat}},
                    lng: {{$contact->lng}}
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
@endsection
