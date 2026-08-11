@extends('includes_admin.mainlayout')
@section('content')
    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>{{ trans('admin.edit_profile') }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i>{{ trans('admin.dashboard') }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('admin.edit_profile') }}</li>
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
                                  action="{{ route('edit-profile', Auth::user()->id) }}"
                                  method="POST" enctype="multipart/form-data">
                                {{ method_field('POST') }}
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div id="shoplocation" style="width:100%;height:350px"></div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label
                                                for="validationCustom04"> {{ trans('admin.name') }}</label>
                                            <input class="form-control" type="text" name="name"
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.name') }}"
                                                   value="{{Auth::user()->name}}"
                                                   required>
                                            <div class="invalid-feedback">{{ $errors->first('name') }}.</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom04">{{ trans('admin.mobile_number') }}</label>
                                            <input class="form-control" type="number" name="phone"
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.mobile_number') }}"
                                                   value="{{Auth::user()->phone}}"
                                                   required>
                                            <div class="invalid-feedback">{{ $errors->first('mobile_number') }}.</div>
                                        </div>

                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom04">{{ trans('admin.accounts.email') }}</label>
                                            <input class="form-control" type="email" name="email"
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.accounts.email') }}"
                                                   value="{{\Illuminate\Support\Facades\Auth::user()->email}}"
                                                   required>
                                            <div class="invalid-feedback">{{ $errors->first('email') }}.</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="city_id">@lang('admin.all_cities')</label>
                                            <select name="city_id" id="city_id" class="form-control" required>
                                                {{--                                                <option value="">اختر نوع الفاعلية اولا</option>--}}

                                                @foreach($data['cities'] as $city)
                                                    @if($city->id == Auth::user()->city_id)
                                                        <option
                                                            value="{{$city->id}}"
                                                            selected>{{ $city->name_ar }}</option>
                                                    @else
                                                        <option
                                                            value="{{$city->id}}">{{ app()->getLocale() == 'en' ? $city->name_en :$city->name_ar }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">{{ $errors->first('city_id') }}</div>
                                        </div>


                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom05">@lang('admin.address')</label>
                                            <input type="text" class="form-control" name="address"
                                                   value="{{Auth::user()->address ?? null}}"
                                                   id="autocomplete"
                                                   placeholder="@lang('admin.search')" >
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="date_created">@lang('admin.date_created')</label>
                                            <input class="form-control" type="date" name="date_created"
                                                   id="date_created"
                                                   value="{{ date('Y-m-d',strtotime(\Illuminate\Support\Facades\Auth::user()->date_created))  }}"
                                                   required>
                                            <div class="invalid-feedback">{{ $errors->first('date_created') }}.</div>
                                        </div>


                                    </div>


                                    <div class="form-row">

                                        <div class="col-md-6 mb-3">
                                            <label
                                                for="communication_officer">@lang('messages.auth.communication_officer')</label>
                                            <input type="text" class="form-control" name="communication_officer"
                                                   value="{{\Illuminate\Support\Facades\Auth::user()->communication_officer }}"
                                                   id="communication_officer">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label
                                                for="validationCustom04">{{ trans('admin.communication_officer_phone') }}</label>
                                            <input class="form-control" type="number" name="communication_officer_phone"
                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.communication_officer_phone') }}"
                                                   value="{{Auth::user()->communication_officer_phone}}">
                                            <div
                                                class="invalid-feedback">{{ $errors->first('communication_officer_phone') }}
                                                .
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-2">
                                            <h6 class="mb-0">@lang('admin.social_media')</h6>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="facebook_url">Facebook</label>
                                            <input class="form-control" type="url" name="facebook_url"
                                                   id="facebook_url"
                                                   placeholder="https://facebook.com/..."
                                                   value="{{ old('facebook_url', Auth::user()->facebook_url) }}">
                                            <div class="invalid-feedback d-block">{{ $errors->first('facebook_url') }}</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="instagram_url">Instagram</label>
                                            <input class="form-control" type="url" name="instagram_url"
                                                   id="instagram_url"
                                                   placeholder="https://instagram.com/..."
                                                   value="{{ old('instagram_url', Auth::user()->instagram_url) }}">
                                            <div class="invalid-feedback d-block">{{ $errors->first('instagram_url') }}</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="tiktok_url">TikTok</label>
                                            <input class="form-control" type="url" name="tiktok_url"
                                                   id="tiktok_url"
                                                   placeholder="https://tiktok.com/@..."
                                                   value="{{ old('tiktok_url', Auth::user()->tiktok_url) }}">
                                            <div class="invalid-feedback d-block">{{ $errors->first('tiktok_url') }}</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="snapchat_url">Snapchat</label>
                                            <input class="form-control" type="url" name="snapchat_url"
                                                   id="snapchat_url"
                                                   placeholder="https://snapchat.com/add/..."
                                                   value="{{ old('snapchat_url', Auth::user()->snapchat_url) }}">
                                            <div class="invalid-feedback d-block">{{ $errors->first('snapchat_url') }}</div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="youtube_url">YouTube</label>
                                            <input class="form-control" type="url" name="youtube_url"
                                                   id="youtube_url"
                                                   placeholder="https://youtube.com/..."
                                                   value="{{ old('youtube_url', Auth::user()->youtube_url) }}">
                                            <div class="invalid-feedback d-block">{{ $errors->first('youtube_url') }}</div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-2 mb-3">
                                            <label for="image"></label>
                                            <img src="{{ Auth::user()->image }}"
                                                 style="width: 80px;height: 80px"
                                                 class="img-thumbnail image-preview img-80 rounded-circle" alt="">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom05">{{ trans('admin.image') }}</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file"
                                                       name="image">
                                                <label class="custom-file-label"
                                                       for="validatedCustomFile">{{ trans('admin.select') }}
                                                </label>
                                                <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-3">
                                            <label for="qr_code"></label>

                                            <img src="{{ Auth::user()->qr_code }}"
                                                 style="width: 80px;height: 80px"
                                                 class="img-thumbnail image-preview img-80 rounded-circle" alt="">

                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="qr_code">{{ trans('messages.auth.qr_code_upload') }}</label>
                                            <div class="custom-file">
                                                <input class="custom-file-input" type="file"
                                                       id="qr_code"
                                                       name="qr_code">
                                                <label class="custom-file-label"
                                                       for="validatedCustomFile">{{ trans('admin.select') }}
                                                </label>
                                                <div class="invalid-feedback">{{ $errors->first('qr_code') }}</div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="info">@lang('messages.auth.info')</label>
                                            <textarea class="form-control" name="info" required id="info"
                                                      placeholder=" @lang('messages.auth.info')">{{\Illuminate\Support\Facades\Auth::user()->info }}</textarea>
                                            <div class="invalid-feedback">{{ $errors->first('info') }}
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6 mb-3" style="display: none">
                                            <label for="validationCustom04">Latitude</label>
                                            <input type="text"  class="form-control" name="lat"
                                                   value="{{Auth::user()->lat ?? 0.0}}"
                                                   id="lat" placeholder="Enter lat">
                                        </div>
                                        <div class="col-md-6 mb-3" style="display: none">
                                            <label for="validationCustom04">longitude</label>
                                            <input type="text"  class="form-control" name="lng"
                                                   value="{{Auth::user()->lng ?? 0.0}}"
                                                   id="lng" placeholder="Enter long">
                                        </div>
                                    </div>

                                </div>


                                <div class="col-md-4 mb-3">
                                    <button class="btn btn-primary"
                                            type="submit">{{ trans('admin.edit') }}
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

        var _URL = window.URL || window.webkitURL;
        $("#image").change(function (e) {
            var file, img;
            if ((file = this.files[0])) {
                img = new Image();
                var objectUrl = _URL.createObjectURL(file);
                img.onload = function () {
                    var height_event = 99;
                    var width_event = 99;


                    var height = this.height;
                    var width = this.width;

                    if ((height != height_event) && (width != width_event)) {
                        $("[name='image']").val('');
                        alert("يرجى رفع المقاسات المطلوبة.");
                        return false;
                    }
                    // alert("تم رفع الصورة بالمقاسات المطلوبة");
                    // return true;
                };
                img.src = objectUrl;
            }
        });


        var marker = null;
        var placeSearch, autocomplete;

        function initMap() {
            autocomplete =
                new google.maps.places.Autocomplete((document.getElementById('autocomplete')),
                );
            var map = new google.maps.Map(document.getElementById('shoplocation'), {
                zoom: 13,
                center: {
                    lat: {{ $data['clinic']->lat ?? 0.0 }},
                    lng: {{ $data['clinic']->lng ?? 0.0 }},
                }
            });
            var MaekerPos = new google.maps.LatLng({{ $data['clinic']->lat ?? 0.0 }}, {{ $data['clinic']->lng ?? 0.0 }});
            marker = new google.maps.Marker({
                position: MaekerPos,
                map: map,
                label: {
                    text: "{{ $data['clinic']->address ?? '' }} ",
                }
            });
            // Add a marker at the center of the map.
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
