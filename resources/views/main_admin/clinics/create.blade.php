@extends('layout_new.mainlayout')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/select2.css') }}">

    <style>
        /* تحسينات بسيطة إضافية */
        .map-container {
            height: 350px;
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 1rem;
        }
        .card-body {
            padding: 2rem;
        }
        .btn-submit {
            padding: 0.75rem 2rem;
            font-weight: 500;
            border-radius: 0.5rem;
        }
        /* Alert styles */
        .alert-custom {
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .alert-custom i {
            font-size: 1.25rem;
        }
        .alert-danger-custom {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
            color: #c92a2a;
            border-left: 5px solid #c92a2a;
        }
        .alert-success-custom {
            background: linear-gradient(135deg, #f0fff4 0%, #d3f9d8 100%);
            color: #2b8a3e;
            border-left: 5px solid #2b8a3e;
        }
        .alert-warning-custom {
            background: linear-gradient(135deg, #fff9db 0%, #fff3bf 100%);
            color: #e67700;
            border-left: 5px solid #e67700;
        }
        .error-list {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 0 0;
        }
        .error-list li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0;
        }
        .error-list li i {
            font-size: 0.875rem;
            color: #c92a2a;
        }
    </style>

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-clinic-medical me-2 text-primary"></i>
                    @lang('main.Add Clinic')
                </h4>
                <a href="{{ route('clinics') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    @lang('main.Back')
                </a>
            </div>

            <!-- Error Messages Section - يظهر في مكان واضح أعلى الفورم -->
            @if($errors->any() || session('error') || session('failed') || session('success'))
                <div class="alert-custom
                    @if(session('success')) alert-success-custom
                    @elseif(session('error') || session('failed') || $errors->any()) alert-danger-custom
                    @endif mb-4">

                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            @if(session('success'))
                                <i class="fas fa-check-circle fa-2x"></i>
                            @elseif(session('error') || session('failed') || $errors->any())
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            @if(session('success'))
                                <h5 class="mb-1 fw-bold">@lang('main.Success')</h5>
                                <p class="mb-0">{{ session('success') }}</p>
                            @endif

                            @if(session('error'))
                                <h5 class="mb-1 fw-bold">@lang('main.Error')</h5>
                                <p class="mb-0">{{ session('error') }}</p>
                            @endif

                            @if(session('failed'))
                                <h5 class="mb-1 fw-bold">@lang('main.Failed')</h5>
                                <p class="mb-0">{{ session('failed') }}</p>
                            @endif

                            @if($errors->any())
                                <h5 class="mb-2 fw-bold">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    @lang('main.Please fix the following errors')
                                </h5>
                                <ul class="error-list">
                                    @foreach($errors->all() as $error)
                                        <li>
                                            <i class="fas fa-times-circle"></i>
                                            {{ $error }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form class="needs-validation"
                          action="{{ route('create-clinic') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          autocomplete="off">

                        @csrf
                        @method('POST')

                        <!-- Map Section -->
                        <div class="mb-5">
                            <h5 class="form-section-title">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                @lang('main.Location Information')
                            </h5>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label required-field">@lang('main.Address')</label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('address') is-invalid @enderror"
                                           name="address"
                                           id="autocomplete"
                                           placeholder="@lang('main.Search for location')"
                                           value="{{ old('address') }}"
                                           required>
                                    <div class="invalid-feedback">
                                        @error('address')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please enter an address')
                                            @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="shoplocation" class="map-container"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information Section -->
                        <div class="mb-5">
                            <h5 class="form-section-title">
                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                @lang('main.Basic Information')
                            </h5>
                            <div class="row g-4">
                                <!-- Clinic Name -->
                                <div class="col-md-6">
                                    <label class="form-label required-field">@lang('main.Clinic Name')</label>
                                    <input class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           type="text"
                                           name="name"
                                           placeholder="@lang('main.Enter clinic name')"
                                           value="{{ old('name') }}"
                                           required>
                                    <div class="invalid-feedback">
                                        @error('name')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please enter clinic name')
                                            @enderror
                                    </div>
                                </div>

                                <!-- Clinic Description -->
                                <div class="col-md-6">
                                    <label class="form-label required-field">@lang('main.Clinic Description')</label>
                                    <textarea class="form-control form-control-lg @error('desc') is-invalid @enderror"
                                              name="desc"
                                              rows="1"
                                              required>{{ old('desc') }}</textarea>
                                    <div class="invalid-feedback">
                                        @error('desc')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please enter description')
                                            @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="mb-5">
                            <h5 class="form-section-title">
                                <i class="fas fa-phone-alt me-2 text-primary"></i>
                                @lang('main.Contact Information')
                            </h5>
                            <div class="row g-4">
                                <!-- Phone Number -->
                                <div class="col-md-4">
                                    <label class="form-label required-field">@lang('main.Phone Number')</label>
                                    <input class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                           type="tel"
                                           name="phone"
                                           placeholder="@lang('main.Enter phone number')"
                                           value="{{ old('phone') }}"
                                           required>
                                    <div class="invalid-feedback">
                                        @error('phone')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please enter phone number')
                                            @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-4">
                                    <label class="form-label required-field">@lang('main.Email')</label>
                                    <input class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           type="email"
                                           name="email"
                                           placeholder="@lang('main.Enter email')"
                                           value="{{ old('email') }}"
                                           required>
                                    <div class="invalid-feedback">
                                        @error('email')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please enter a valid email')
                                            @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-4">
                                    <label class="form-label required-field">@lang('main.Password')</label>
                                    <input class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           type="password"
                                           name="password"
                                           placeholder="@lang('main.Enter password')"
                                           required>
                                    <div class="invalid-feedback">
                                        @error('password')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please enter password')
                                            @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Classification Section -->
                        <div class="mb-5">
                            <h5 class="form-section-title">
                                <i class="fas fa-tags me-2 text-primary"></i>
                                @lang('main.Classification')
                            </h5>
                            <div class="row g-4">
                                <!-- Specialties -->
                                <div class="col-md-6">
                                    <label class="form-label required-field">@lang('main.Clinic Specialties')</label>
                                    <select id="specialty_id"
                                            class="js-example-placeholder-multiple form-control form-control-lg @error('specialty_id') is-invalid @enderror"
                                            name="specialty_id[]"
                                            multiple
                                            required>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty->id }}" {{ in_array($specialty->id, old('specialty_id', [])) ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'en' ? $specialty->name_en : $specialty->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('specialty_id')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please select at least one specialty')
                                            @enderror
                                    </div>
                                </div>

                                <!-- City -->
                                <div class="col-md-6">
                                    <label class="form-label required-field">@lang('main.Cities')</label>
                                    <select id="city_id"
                                            class="js-example-placeholder-multiple form-control form-control-lg @error('city_id') is-invalid @enderror"
                                            name="city_id"
                                            required>
                                        <option value="" disabled {{ old('city_id') ? '' : 'selected' }}>@lang('main.Select')</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'en' ? $city->name_en : $city->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('city_id')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please select a city')
                                            @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Package & Image Section -->
                        <div class="mb-5">
                            <h5 class="form-section-title">
                                <i class="fas fa-cube me-2 text-primary"></i>
                                @lang('main.Package & Image')
                            </h5>
                            <div class="row g-4">
                                <!-- Package -->
                                <div class="col-md-6">
                                    <label class="form-label required-field">@lang('main.Choose Package')</label>
                                    <select name="package_id" class="form-control form-control-lg @error('package_id') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('package_id') ? '' : 'selected' }}>@lang('main.Select')</option>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'en' ? $package->name_en : $package->name_ar }}
                                                ({{ $package->duration }} @lang('main.days'))
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('package_id')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please select a package')
                                            @enderror
                                    </div>
                                </div>

                                <!-- Image -->
                                <div class="col-md-6">
                                    <label class="form-label required-field">@lang('main.Clinic Image')</label>
                                    <input class="form-control form-control-lg @error('image') is-invalid @enderror"
                                           type="file"
                                           name="image"
                                           accept="image/*"
                                           required>
                                    <div class="invalid-feedback">
                                        @error('image')
                                        {{ $message }}
                                        @else
                                            @lang('main.Please select an image')
                                            @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Section -->
                        <div class="mb-5">
                            <h5 class="form-section-title">
                                <i class="fas fa-file-contract me-2 text-primary"></i>
                                @lang('main.contract_booking_system')
                            </h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">@lang('main.contract_model')</label>
                                    <select name="contract_model" id="contract_model" class="form-control form-control-lg">
                                        <option value="cash_commission" {{ old('contract_model', 'cash_commission') === 'cash_commission' ? 'selected' : '' }}>@lang('main.contract_cash_commission')</option>
                                        <option value="online_payment_commission" {{ old('contract_model') === 'online_payment_commission' ? 'selected' : '' }}>@lang('main.contract_online_payment_commission')</option>
                                        <option value="annual_subscription" {{ old('contract_model') === 'annual_subscription' ? 'selected' : '' }}>@lang('main.contract_annual_subscription')</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">@lang('main.contract_payment_method')</label>
                                    <select name="payment_method" class="form-control form-control-lg">
                                        <option value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>@lang('main.payment_cash')</option>
                                        <option value="online" {{ old('payment_method') === 'online' ? 'selected' : '' }}>@lang('main.payment_online')</option>
                                    </select>
                                </div>
                                <div class="col-md-6 contract-commission-field">
                                    <label class="form-label">@lang('main.platform_commission_rate')</label>
                                    <input class="form-control form-control-lg @error('commission_rate') is-invalid @enderror"
                                           type="number"
                                           name="commission_rate"
                                           min="0"
                                           max="100"
                                           step="0.01"
                                           value="{{ old('commission_rate', 0) }}">
                                </div>
                                <div class="col-md-4 contract-subscription-field">
                                    <label class="form-label">@lang('main.annual_subscription_amount')</label>
                                    <input class="form-control form-control-lg @error('annual_subscription_amount') is-invalid @enderror"
                                           type="number"
                                           name="annual_subscription_amount"
                                           min="0"
                                           step="0.01"
                                           value="{{ old('annual_subscription_amount') }}">
                                </div>
                                <div class="col-md-4 contract-subscription-field">
                                    <label class="form-label">@lang('main.annual_subscription_starts_at')</label>
                                    <input class="form-control form-control-lg @error('annual_subscription_starts_at') is-invalid @enderror"
                                           type="date"
                                           name="annual_subscription_starts_at"
                                           value="{{ old('annual_subscription_starts_at') }}">
                                </div>
                                <div class="col-md-4 contract-subscription-field">
                                    <label class="form-label">@lang('main.annual_subscription_ends_at')</label>
                                    <input class="form-control form-control-lg @error('annual_subscription_ends_at') is-invalid @enderror"
                                           type="date"
                                           name="annual_subscription_ends_at"
                                           value="{{ old('annual_subscription_ends_at') }}">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="rendezvous_badge_enabled" value="1" id="rendezvous_badge_enabled" {{ old('rendezvous_badge_enabled') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rendezvous_badge_enabled">@lang('main.rendezvous_badge_enabled')</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Section -->
                        <div class="mb-5">
                            <h5 class="form-section-title">
                                <i class="fas fa-share-alt me-2 text-primary"></i>
                                Social Media
                            </h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Facebook</label>
                                    <input class="form-control form-control-lg @error('facebook_url') is-invalid @enderror"
                                           type="url"
                                           name="facebook_url"
                                           value="{{ old('facebook_url') }}"
                                           placeholder="https://facebook.com/...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Instagram</label>
                                    <input class="form-control form-control-lg @error('instagram_url') is-invalid @enderror"
                                           type="url"
                                           name="instagram_url"
                                           value="{{ old('instagram_url') }}"
                                           placeholder="https://instagram.com/...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">TikTok</label>
                                    <input class="form-control form-control-lg @error('tiktok_url') is-invalid @enderror"
                                           type="url"
                                           name="tiktok_url"
                                           value="{{ old('tiktok_url') }}"
                                           placeholder="https://tiktok.com/@...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Snapchat</label>
                                    <input class="form-control form-control-lg @error('snapchat_url') is-invalid @enderror"
                                           type="url"
                                           name="snapchat_url"
                                           value="{{ old('snapchat_url') }}"
                                           placeholder="https://snapchat.com/add/...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">YouTube</label>
                                    <input class="form-control form-control-lg @error('youtube_url') is-invalid @enderror"
                                           type="url"
                                           name="youtube_url"
                                           value="{{ old('youtube_url') }}"
                                           placeholder="https://youtube.com/...">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="register" value="1">
                        <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}">
                        <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}">

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-5">
                            <button class="btn btn-primary btn-submit" type="submit">
                                <i class="fas fa-save me-2"></i>
                                @lang('main.Register')
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

        @component('components.footer')@endcomponent
    </div>

    <!-- Google Maps Script -->
    <script>
        let map, marker, autocomplete;

        function initMap() {
            const defaultLocation = { lat: 21.485811, lng: 39.192505 };

            map = new google.maps.Map(document.getElementById('shoplocation'), {
                zoom: 12,
                center: defaultLocation,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP
            });

            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById('autocomplete'),
                { types: ['geocode'] }
            );

            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;
                setMarker(place.geometry.location);
            });

            map.addListener('click', function (e) {
                setMarker(e.latLng);
            });

            marker.addListener('dragend', function (e) {
                setLatLng(e.latLng);
            });

            // استعادة الموقع المحفوظ إذا وجد
            @if(old('lat') && old('lng'))
            const savedLocation = { lat: {{ old('lat') }}, lng: {{ old('lng') }} };
            setMarker(savedLocation);
            @else
            setLatLng(marker.getPosition());
            @endif
        }

        function setMarker(location) {
            marker.setPosition(location);
            map.panTo(location);
            setLatLng(location);
        }

        function setLatLng(location) {
            document.getElementById('lat').value = location.lat();
            document.getElementById('lng').value = location.lng();
        }
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPf96eskAPXvkyDLPyYhxSCAKIziCUG_E&libraries=places&callback=initMap">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const model = document.getElementById('contract_model');
            const subscriptionFields = document.querySelectorAll('.contract-subscription-field');
            const commissionFields = document.querySelectorAll('.contract-commission-field');

            function toggleContractFields() {
                const isSubscription = model.value === 'annual_subscription';
                subscriptionFields.forEach(field => field.style.display = isSubscription ? '' : 'none');
                commissionFields.forEach(field => field.style.display = isSubscription ? 'none' : '');
            }

            if (model) {
                model.addEventListener('change', toggleContractFields);
                toggleContractFields();
            }
        });
    </script>

@endsection
