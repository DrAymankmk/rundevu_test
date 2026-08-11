<?php $page = 'emergency-hospitals'; ?>
@extends('layout_new.mainlayout')

@section('content')
    <style>
        .map-container {
            height: 350px;
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
    </style>

    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-bold mb-0">مستشفى طوارئ</h4>
                <a href="{{ route('emergency-hospitals.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method($method)

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Arabic Name *</label>
                                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $hospital->name_ar) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">English Name *</label>
                                <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $hospital->name_en) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $hospital->phone) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City *</label>
                                <select name="city_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id', $hospital->city_id) == $city->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'en' ? $city->name_en : $city->name_ar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Region</label>
                                <select name="region_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id', $hospital->region_id) == $region->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'en' ? ($region->name_en ?? $region->name) : ($region->name_ar ?? $region->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <input type="text"
                                       name="address"
                                       id="autocomplete"
                                       class="form-control"
                                       value="{{ old('address', $hospital->address) }}"
                                       placeholder="Search for location">
                            </div>
                            <div class="col-md-12">
                                <div id="hospitalLocationMap" class="map-container"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Latitude *</label>
                                <input type="text" name="lat" id="lat" class="form-control" value="{{ old('lat', $hospital->lat) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude *</label>
                                <input type="text" name="lng" id="lng" class="form-control" value="{{ old('lng', $hospital->lng) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image {{ $hospital->exists ? '' : '*' }}</label>
                                <input type="file" name="image" class="form-control" accept="image/*" {{ $hospital->exists ? '' : 'required' }}>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status', $hospital->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $hospital->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @component('components.footer')@endcomponent
    </div>

    <script>
        let map, marker, autocomplete;

        function initEmergencyHospitalMap() {
            const savedLat = parseFloat(document.getElementById('lat').value);
            const savedLng = parseFloat(document.getElementById('lng').value);
            const defaultLocation = {
                lat: !Number.isNaN(savedLat) && savedLat !== 0 ? savedLat : 21.485811,
                lng: !Number.isNaN(savedLng) && savedLng !== 0 ? savedLng : 39.192505
            };

            map = new google.maps.Map(document.getElementById('hospitalLocationMap'), {
                zoom: 12,
                center: defaultLocation,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true
            });

            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById('autocomplete'),
                { types: ['geocode'] }
            );

            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                setHospitalMarker(place.geometry.location);
            });

            map.addListener('click', function (event) {
                setHospitalMarker(event.latLng);
            });

            marker.addListener('dragend', function (event) {
                setHospitalLatLng(event.latLng);
            });

            setHospitalLatLng(marker.getPosition());
        }

        function setHospitalMarker(location) {
            marker.setPosition(location);
            map.panTo(location);
            setHospitalLatLng(location);
        }

        function setHospitalLatLng(location) {
            document.getElementById('lat').value = location.lat();
            document.getElementById('lng').value = location.lng();
        }
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPf96eskAPXvkyDLPyYhxSCAKIziCUG_E&libraries=places&callback=initEmergencyHospitalMap">
    </script>
@endsection
