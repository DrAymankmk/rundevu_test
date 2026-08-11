@php
$fb = config('app.fallback_locale', 'en');
$locale = app()->getLocale();
$st = $section->translation($locale) ?? $section->translation($fb);
$title = $st?->title ?: __('Expert Doctors, Seamless Appointments, Quality Care');
$sub = $st?->subtitle ?: __('About us');
$desc = $st?->description ?: '<p class="fs-18 mb-30 wow fadeInUp" data-wow-delay=".1s">' . e(__('A belief that knowledge
	is power—we connect our patients with their results and quality care when they need it most.')) . '</p>';
$descHasListItems = stripos($desc, '<li') !== false;
$img = $section->getMediaUrl('images', $locale, asset('frontend/assets/img/normal/about_1_1.jpg'), true);

$specialists = \App\Models\Specialty::whereNull('parent_id')->whereNull('deleted_at')->orderBy('id')->get();
$oldSpecialists = array_map('strval', old('specialist', []));
$subscriptionIsRtl = in_array(explode('-', strtolower(str_replace('_', '-', $locale)))[0], ['ar', 'fa', 'he', 'ur'], true);

$packages = $packages ?? \App\Models\Package::query()->where('status', 1)->orderBy('price')->get();
$selectedPackageId = old('package', $selectedPackageId ?? (request()->filled('package') ? (int)
request()->query('package') : null));
if ($selectedPackageId) {
$selectedPackageId = (int) $selectedPackageId;
}
$selectedPackage = $selectedPackage ?? ($selectedPackageId ? $packages->first(function ($pkg) use ($selectedPackageId) {
return (int) $pkg->id === (int) $selectedPackageId;
}) : null);

$parseFeatures = static function (?string $text): array {
if (blank($text)) {
return [];
}
if (preg_match('/<li[^>]*>/i', $text)) {
	preg_match_all('/<li[^>]*>(.*?)<\ /li>/is', $text, $matches);

			return collect($matches[1] ?? [])
			->map(static fn ($line) => trim(strip_tags($line)))
			->filter()
			->values()
			->all();
			}

			return collect(preg_split('/\r\n|\r|\n/', strip_tags($text)))
			->map(static fn ($line) => trim($line))
			->filter()
			->values()
			->all();
			};

			$formatPrice = static function ($value): string {
			if ($value === null || $value === '') {
			return '';
			}
			if (is_numeric($value)) {
			return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
			}

			return (string) $value;
			};

			$packageName = static function ($pkg) use ($locale) {
			return $locale === 'ar'
			? ($pkg->name_ar ?: $pkg->name_en)
			: ($pkg->name_en ?: $pkg->name_ar);
			};

			if ($selectedPackage) {
			$selectedFeaturesRaw = $locale === 'ar'
			? ($selectedPackage->features_ar ?: $selectedPackage->features_en)
			: ($selectedPackage->features_en ?: $selectedPackage->features_ar);
			$selectedFeatures = $parseFeatures($selectedFeaturesRaw);
			$selectedPrice = $formatPrice($selectedPackage->price_after_discount ?:
			$selectedPackage->price);
			$selectedOriginalPrice = filled($selectedPackage->price_after_discount)
			&& (string) $selectedPackage->price_after_discount !== (string) $selectedPackage->price
			? $formatPrice($selectedPackage->price)
			: null;
			}
			$resolveHref = static function (?string $raw): string {
			$raw = trim((string) $raw);
			if ($raw === '') {
			return '#';
			}
			if (preg_match('#^(https?:)?//#i', $raw) || str_starts_with($raw, 'mailto:') ||
			str_starts_with($raw, 'tel:')) {
			return $raw;
			}

			return str_starts_with($raw, '/') ? url($raw) : url('/' . ltrim($raw, '/'));
			};

			$aboutButtons = collect();
			if ($section->relationLoaded('links')) {
			foreach ($section->links->where('is_active', true)->filter(static fn ($l) =>
			filled(trim((string) ($l->link ?? ''))))->sortBy('order')->values() as $idx => $link) {
			$href = $resolveHref($link->link);
			$tr = null;
			if ($link->relationLoaded('translations')) {
			$tr = $link->translations->firstWhere('locale', $locale)
			?? $link->translations->firstWhere('locale', $fb)
			?? $link->translations->first();
			}
			$label = $tr?->name ?? $link->name ?? __('Link');
			$target = in_array($link->target, ['_blank', '_self'], true) ? $link->target : '_self';
			$rel = $target === '_blank' ? 'noopener noreferrer' : null;
			$icon = trim((string) ($link->icon ?? ''));
			$type = strtolower((string) ($link->type ?? ''));
			if (in_array($type, ['secondary', 'outline', 'border'], true)) {
			$btnClass = 'th-btn th-border';
			} elseif (in_array($type, ['primary', 'solid', 'main'], true)) {
			$btnClass = 'th-btn style2';
			} else {
			$btnClass = $idx === 0 ? 'th-btn style2' : 'th-btn th-border';
			}
			$aboutButtons->push(compact('href', 'label', 'target', 'rel', 'icon', 'btnClass'));
			}
			}
			// if ($aboutButtons->isEmpty()) {
			// $aboutButtons->push([
			// 'href' => url('/about'),
			// 'label' => __('More About Us'),
			// 'target' => '_self',
			// 'rel' => null,
			// 'icon' => 'fa-light fa-arrow-right-long ms-2',
			// 'btnClass' => 'th-btn style2',
			// ]);
			// }
			@endphp
			<div class="about-area overflow-hidden space-bottom" style="padding: 80px 20px;"
				id="about-sec">
				<div class="container">
					<div class="row gy-4">
						<div class="col-xxl-6 col-xl-12 mb-30 mb-xl-0">
							<div class="title-area">
								<span
									class="sub-title">{{ $sub }}</span>
								<h2
									class="sec-title text-anime-style-3">
									{!! $title !!}</h2>
							</div>
							<div class="img-box1">
								<div class="about-wrapper">

									<div>
										<div
											class="cms-about-desc{{ $descHasListItems ? ' checklist' : '' }}">
											{!! $desc
											!!}</div>
										<div class="btn-group mt-40 wow fadeInUp"
											data-wow-delay=".4s">
											@foreach($aboutButtons as $btn)
											<a href="{{ $btn['href'] }}"
												class="{{ $btn['btnClass'] }}"
												@if(($btn['target']
												?? '_self'
												)==='_blank'
												)
												target="_blank"
												@endif
												@if(!
												empty($btn['rel']))
												rel="{{ $btn['rel'] }}"
												@endif>
												{{ $btn['label'] }}
												@if(!
												empty($btn['icon']))
												<i
													class="{{ $btn['icon'] }}"></i>
												@endif
											</a>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xxl-6 col-xl-12 mb-30 mb-xl-0">
							@php
							$registrationFieldLabels = [
							'clinic_name' => __('main.clinic_name'),
							'specialist' => __('main.select_specialists'),
							'address' => __('main.address'),
							'phone_number' => __('main.phone_number'),
							'alternative_number' =>
							__('main.alternative_number'),
							'admin_name' => __('main.name'),
							'admin_email' => __('main.email'),
							'admin_phone' => __('main.phone_number'),
							'clinic_license_number' =>
							__('main.clinic_license_number'),
							'medical_commercial_license' =>
							__('main.medical_commercial_license'),
							'email_address' => __('main.email_address'),
							'password' => __('main.password'),
							'package' => __('main.package'),
							];
							$registrationErrorStep = 1;
							if ($errors->hasAny(['admin_name', 'admin_email',
							'admin_phone'])) {
							$registrationErrorStep = 2;
							} elseif
							($errors->hasAny(['clinic_license_number',
							'medical_commercial_license'])) {
							$registrationErrorStep = 3;
							} elseif ($errors->hasAny(['email_address',
							'password', 'package'])) {
							$registrationErrorStep = 4;
							}
							@endphp
							<form method="POST"
								action="/subscription/register"
								enctype="multipart/form-data"
								id="clinic-registration-form"
								novalidate>
								@csrf
								<h3 class="h4 mb-30 mt-n3">
									{{ __('main.subscription') }}
								</h3>

								@if(session('success'))
								<div class="alert alert-success">
									{{ session('success') }}</div>
								@endif
								@php
								$registrationCauses =
								collect(session('registration_errors',
								[]))
								->merge($errors->any() ? $errors->all()
								: [])
								->unique()
								->filter()
								->values();
								@endphp
								@if($registrationCauses->isNotEmpty())
								<div class="alert alert-danger"
									role="alert">
									<strong
										class="d-block mb-2">{{ session('registration_error_title', session('error', __('main.registration_failed'))) }}</strong>
									<ul class="mb-0 ps-3">
										@foreach($registrationCauses as $cause)
										<li>{{ $cause }}
										</li>
										@endforeach
									</ul>
								</div>
								@elseif(session('error'))
								<div class="alert alert-danger"
									role="alert">
									{{ session('error') }}</div>
								@endif

								@if($selectedPackage)
								<div class="selected-package-summary mb-30 p-4 border rounded"
									id="selected-package-summary">
									<div
										class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
										<div>
											<span
												class="sub-title d-block mb-1">{{ __('main.selected_package') }}</span>
											<h4
												class="mb-0">
												{{ $packageName($selectedPackage) }}
											</h4>
										</div>
										<h4
											class="mb-0 box-price">
											{{ $selectedPrice }}
											@if($selectedOriginalPrice)
											<small
												class="text-decoration-line-through opacity-75 ms-1">{{ $selectedOriginalPrice }}</small>
											@endif
											@if($selectedPackage->duration)
											<span
												class="fs-16 fw-normal">/
												{{ __('main.duration') }}:
												{{ $selectedPackage->duration }}
												{{ __('main.days') }}</span>
											@endif
										</h4>
									</div>
									<!-- @if(filled($selectedPackage->discount))
                                    <p class="mb-2"><span class="badge bg-success">{{ __('admin.discount') }}: {{ $selectedPackage->discount }}%</span></p>
                                @endif
                                @if($selectedPackage->free_months > 0)
                                    <p class="mb-2"><span class="badge bg-info">{{ __('main.free_months') }}: {{ $selectedPackage->free_months }}</span></p>
                                @endif -->
									@if(!empty($selectedFeatures))
									<ul class="mb-0 ps-3">
										@foreach($selectedFeatures as $feature)
										<li>{{ $feature }}
										</li>
										@endforeach
									</ul>
									@elseif(!empty($selectedFeaturesRaw))
									<p class="mb-0">{!!
										nl2br(e(strip_tags($selectedFeaturesRaw)))
										!!}</p>
									@endif
								</div>
								@endif

								{{-- Step Indicators --}}
								<div class="step-indicators mb-40">
									<div class="step-indicator active"
										data-step="1">
										<span
											class="step-number">1</span>
										<span
											class="step-label">{{ __('main.clinic_main_info') }}</span>
									</div>
									<div class="step-indicator"
										data-step="2">
										<span
											class="step-number">2</span>
										<span
											class="step-label">{{ __('main.admin_info') }}</span>
									</div>
									<div class="step-indicator"
										data-step="3">
										<span
											class="step-number">3</span>
										<span
											class="step-label">{{ __('main.verification_info') }}</span>
									</div>
									<div class="step-indicator"
										data-step="4">
										<span
											class="step-number">4</span>
										<span
											class="step-label">{{ __('main.account_info') }}</span>
									</div>
								</div>

								{{-- Step 1: Clinic Main Info --}}
								<div class="form-step active"
									id="step-1">
									<h5 class="mb-20"><i
											class="fal fa-hospital me-2"></i>{{ __('main.clinic_main_info') }}
									</h5>
									<div class="row">
										<div class="form-group col-12"
											data-field-wrap="clinic_name">
											<input type="text"
												class="form-control @error('clinic_name') is-invalid @enderror"
												name="clinic_name"
												value="{{ old('clinic_name') }}"
												placeholder="{{ __('main.clinic_name') }}"
												data-validate="required|max:255"
												autocomplete="organization">
											<i
												class="fal fa-clinic-medical"></i>
											<div class="invalid-feedback"
												data-field-error="clinic_name">
												@error('clinic_name'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-12"
											data-field-wrap="specialist"
											data-validate-group="specialist">
											<label class="form-label mb-2" for="specialist-select">{{ __('main.select_specialists') }}</label>
											<select id="specialist-select"
												name="specialist[]"
												class="form-select specialist-select2 @error('specialist') is-invalid @enderror @error('specialist.*') is-invalid @enderror"
												multiple
												data-placeholder="{{ __('main.select_specialists') }}">
												@foreach($specialists as $specialist)
												<option value="{{ $specialist->id }}"
													@if(in_array((string) $specialist->id, $oldSpecialists, true)) selected @endif>
													{{ app()->getLocale() === 'ar' ? $specialist->name_ar : $specialist->name_en }}
												</option>
												@endforeach
											</select>
											<div class="invalid-feedback"
												data-field-error="specialist">
												@error('specialist'){{ $message }}@enderror
												@error('specialist.*'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-12"
											data-field-wrap="address">
											<input type="text"
												class="form-control @error('address') is-invalid @enderror"
												name="address"
												value="{{ old('address') }}"
												placeholder="{{ __('main.address') }}"
												data-validate="required|max:500"
												autocomplete="street-address">
											<i
												class="fal fa-map-marker-alt"></i>
											<div class="invalid-feedback"
												data-field-error="address">
												@error('address'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-md-6"
											data-field-wrap="phone_number">
											<input type="tel"
												class="form-control @error('phone_number') is-invalid @enderror"
												name="phone_number"
												value="{{ old('phone_number') }}"
												placeholder="{{ __('main.phone_number') }}"
												data-validate="required|phone|max:50"
												autocomplete="tel"
												inputmode="tel">
											<i
												class="fal fa-phone"></i>
											<div class="invalid-feedback"
												data-field-error="phone_number">
												@error('phone_number'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-md-6"
											data-field-wrap="alternative_number">
											<input type="tel"
												class="form-control @error('alternative_number') is-invalid @enderror"
												name="alternative_number"
												value="{{ old('alternative_number') }}"
												placeholder="{{ __('main.alternative_number') }}"
												data-validate="phone|max:50"
												autocomplete="tel"
												inputmode="tel">
											<i
												class="fal fa-phone-alt"></i>
											<div class="invalid-feedback"
												data-field-error="alternative_number">
												@error('alternative_number'){{ $message }}@enderror
											</div>
										</div>
									</div>
								</div>

								{{-- Step 2: Admin Info --}}
								<div class="form-step" id="step-2">
									<h5 class="mb-20"><i
											class="fal fa-user-shield me-2"></i>{{ __('main.admin_info') }}
									</h5>
									<div class="row">
										<div class="form-group col-12"
											data-field-wrap="admin_name">
											<input type="text"
												class="form-control @error('admin_name') is-invalid @enderror"
												name="admin_name"
												value="{{ old('admin_name') }}"
												placeholder="{{ __('main.name') }}"
												data-validate="required|max:255"
												autocomplete="name">
											<i
												class="fal fa-user"></i>
											<div class="invalid-feedback"
												data-field-error="admin_name">
												@error('admin_name'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-md-6"
											data-field-wrap="admin_email">
											<input type="email"
												class="form-control @error('admin_email') is-invalid @enderror"
												name="admin_email"
												value="{{ old('admin_email') }}"
												placeholder="{{ __('main.email') }}"
												data-validate="required|email|max:255"
												autocomplete="email">
											<i
												class="fal fa-envelope"></i>
											<div class="invalid-feedback"
												data-field-error="admin_email">
												@error('admin_email'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-md-6"
											data-field-wrap="admin_phone">
											<input type="tel"
												class="form-control @error('admin_phone') is-invalid @enderror"
												name="admin_phone"
												value="{{ old('admin_phone') }}"
												placeholder="{{ __('main.phone_number') }}"
												data-validate="required|phone|max:50"
												autocomplete="tel"
												inputmode="tel">
											<i
												class="fal fa-phone"></i>
											<div class="invalid-feedback"
												data-field-error="admin_phone">
												@error('admin_phone'){{ $message }}@enderror
											</div>
										</div>
									</div>
								</div>

								{{-- Step 3: Verification Info --}}
								<div class="form-step" id="step-3">
									<h5 class="mb-20"><i
											class="fal fa-file-certificate me-2"></i>{{ __('main.verification_info') }}
									</h5>
									<div class="row">
										<div class="form-group col-12"
											data-field-wrap="clinic_license_number">
											<input type="text"
												class="form-control @error('clinic_license_number') is-invalid @enderror"
												name="clinic_license_number"
												value="{{ old('clinic_license_number') }}"
												placeholder="{{ __('main.clinic_license_number') }}"
												data-validate="required|max:100">
											<i
												class="fal fa-id-card"></i>
											<div class="invalid-feedback"
												data-field-error="clinic_license_number">
												@error('clinic_license_number'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-12"
											data-field-wrap="medical_commercial_license">
											<label
												class="form-label fw-medium">{{ __('main.medical_commercial_license') }}</label>
											<input type="file"
												class="form-control @error('medical_commercial_license') is-invalid @enderror"
												name="medical_commercial_license"
												accept="image/jpeg,image/png,image/gif,image/webp,image/jpg"
												data-validate="required|image|max:5120">
											<div class="invalid-feedback"
												data-field-error="medical_commercial_license">
												@error('medical_commercial_license'){{ $message }}@enderror
											</div>
										</div>
									</div>
								</div>

								{{-- Step 4: Account Info --}}
								<div class="form-step" id="step-4">
									<h5 class="mb-20"><i
											class="fal fa-lock me-2"></i>{{ __('main.account_info') }}
									</h5>
									<div class="row">
										<div class="form-group col-12"
											data-field-wrap="email_address">
											<input type="email"
												class="form-control @error('email_address') is-invalid @enderror"
												name="email_address"
												value="{{ old('email_address') }}"
												placeholder="{{ __('main.email_address') }}"
												data-validate="required|email|max:255"
												autocomplete="email">
											<i
												class="fal fa-envelope"></i>
											<div class="invalid-feedback"
												data-field-error="email_address">
												@error('email_address'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-12"
											data-field-wrap="password">
											<input type="password"
												class="form-control @error('password') is-invalid @enderror"
												name="password"
												placeholder="{{ __('main.password') }}"
												data-validate="required|password:8|max:255"
												autocomplete="new-password"
												minlength="8">
											<i
												class="fal fa-lock"></i>
											<div class="invalid-feedback"
												data-field-error="password">
												@error('password'){{ $message }}@enderror
											</div>
										</div>
										<div class="form-group col-12"
											data-field-wrap="package">
											<label
												class="form-label fw-medium mb-2 d-block">{{ __('main.package') }}</label>
											<select name="package"
												id="checkout-package-select"
												class="form-select nice-select @error('package') is-invalid @enderror"
												data-validate="required"
												data-selected-package="{{ old('package', $selectedPackageId ?? '') }}">
												<option value=""
													disabled
													@if(!old('package',
													$selectedPackageId))
													selected
													@endif
													hidden>
													{{ __('main.select_package') }}
												</option>
												@foreach($packages as $pkg)
												<option value="{{ $pkg->id }}"
													@if((int)
													old('package',
													$selectedPackageId)===(int)
													$pkg->
													id)
													selected
													@endif>
													{{ $packageName($pkg) }}
													—
													{{ $formatPrice($pkg->price_after_discount ?: $pkg->price) }}
													@if($pkg->duration)
													({{ $pkg->duration }}
													{{ __('main.days') }})
													@endif
												</option>
												@endforeach
											</select>
											<div class="invalid-feedback"
												data-field-error="package">
												@error('package'){{ $message }}@enderror
											</div>
										</div>
									</div>
								</div>

								{{-- Navigation Buttons --}}
								<div
									class="form-navigation mt-30 d-flex justify-content-between">
									<button type="button"
										class="th-btn th-border btn-prev"
										style="display:none;">
										<i
											class="fal fa-arrow-right mx-2"></i>{{ __('main.previous') }}
									</button>
									<button type="button"
										class="th-btn btn-next ms-auto">
										{{ __('main.next') }}<i
											class="fal fa-arrow-left mx-2"></i>
									</button>
									<button type="submit"
										class="th-btn btn-submit ms-auto"
										style="display:none;">
										{{ __('main.submit_registration') }}<i
											class="fal fa-check ms-2"></i>
									</button>
								</div>
								<p class="form-messages mb-0 mt-3"></p>
							</form>

							<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}">

							<style>
							.step-indicators {
								display: flex;
								justify-content: space-between;
								position: relative
							}

							.step-indicators::before {
								content: '';
								position: absolute;
								top: 20px;
								left: 0;
								right: 0;
								height: 2px;
								background: #e0e0e0;
								z-index: 0
							}

							.step-indicator {
								display: flex;
								flex-direction: column;
								align-items: center;
								position: relative;
								z-index: 1;
								flex: 1
							}

							.step-number {
								width: 40px;
								height: 40px;
								border-radius: 50%;
								background: #e0e0e0;
								display: flex;
								align-items: center;
								justify-content: center;
								font-weight: 700;
								color: #666;
								margin-bottom: 8px;
								transition: all .3s ease
							}

							.step-indicator.active .step-number,
							.step-indicator.completed .step-number {
								background: var(--theme-color, #3b82f6);
								color: #fff
							}

							.step-indicator.completed .step-number {
								background: #22c55e
							}

							.step-label {
								font-size: 12px;
								color: #666;
								text-align: center;
								white-space: nowrap
							}

							.step-indicator.active .step-label {
								color: var(--theme-color, #3b82f6);
								font-weight: 600
							}

							.form-step {
								display: none
							}

							.form-step.active {
								display: block;
								animation: stepFadeIn .3s ease
							}

							@keyframes stepFadeIn {
								from {
									opacity: 0;
									transform: translateY(10px)
								}

								to {
									opacity: 1;
									transform: translateY(0)
								}
							}

							#clinic-registration-form .form-group {
								position: relative
							}

							#clinic-registration-form .form-field-icons {
								position: absolute;
								top: 40%;
								inset-inline-end: 12px;
								inset-inline-start: auto;
								transform: translateY(-50%);
								display: inline-flex;
								flex-direction: row;
								align-items: center;
								justify-content: center;
								gap: .5rem;
								pointer-events: none;
								z-index: 2;
								line-height: 1;
								margin: 0 !important
							}

							#clinic-registration-form .form-field-icon,
							#clinic-registration-form .form-field-error-icon {
								display: inline-flex;
								align-items: center;
								justify-content: center;
								width: 1rem;
								height: 1rem;
								font-size: 15px;
								line-height: 1;
								margin: 0 !important;
								padding: 0 !important;
								top: auto !important;
								right: auto !important;
								left: auto !important;
								transform: none !important;
								position: static !important
							}

							#clinic-registration-form .form-field-icon {
								color: #999
							}

							#clinic-registration-form .form-field-error-icon {
								display: none;
								color: var(--error-color, #dc3545)
							}

							#clinic-registration-form .form-group.has-error .form-field-error-icon {
								display: inline-flex
							}

							#clinic-registration-form .form-group .form-control,
							#clinic-registration-form .form-group .form-select {
								padding-inline-end: 4.25rem;
								padding-inline-start: 15px
							}

							#clinic-registration-form .form-control.is-invalid,
							#clinic-registration-form .form-select.is-invalid {
								background-image: none !important;
								background-position: unset !important
							}

							#clinic-registration-form .form-group.has-error .form-control,
							#clinic-registration-form .form-group.has-error .form-select.is-invalid {
								padding-inline-end: 5.5rem !important
							}

							/* LTR (English): icons on the right */
							html[dir="ltr"] #clinic-registration-form .form-field-icons {
								right: 12px;
								left: auto
							}

							html[dir="ltr"] #clinic-registration-form .form-group .form-control,
							html[dir="ltr"] #clinic-registration-form .form-group .form-select {
								padding-right: 4.25rem;
								padding-left: 15px
							}

							html[dir="ltr"] #clinic-registration-form .form-group.has-error .form-control,
							html[dir="ltr"] #clinic-registration-form .form-group.has-error .form-select {
								padding-right: 5.5rem !important
							}

							/* RTL (Arabic): icons on the left */
							html[dir="rtl"] #clinic-registration-form .form-field-icons {
								left: 20px;
								right: auto
							}

							html[dir="rtl"] #clinic-registration-form .form-group .form-control,
							html[dir="rtl"] #clinic-registration-form .form-group .form-select {
								padding-left: 4.25rem;
								padding-right: 15px;
								text-align: right;
								direction: rtl
							}

							html[dir="rtl"] #clinic-registration-form .form-group.has-error .form-control,
							html[dir="rtl"] #clinic-registration-form .form-group.has-error .form-select {
								padding-left: 5.5rem !important
							}

							html[dir="rtl"] #clinic-registration-form .form-group .form-control::placeholder {
								text-align: right
							}

							html[dir="rtl"] #clinic-registration-form .form-group .form-select option {
								direction: rtl;
								text-align: right
							}

							#clinic-registration-form .form-group:has(.nice-select) .form-field-icons {
								top: 50%
							}

							.btn-prev,
							.btn-prev:hover,
							.btn-prev:focus,
							.btn-prev:active {
								background: transparent !important;
								color: var(--theme-color, #3b82f6) !important;
								border-color: var(--theme-color, #3b82f6) !important;
								box-shadow: none !important;
								transform: none !important
							}

							#clinic-registration-form .invalid-feedback {
								display: none;
								font-size: .875rem;
								color: #dc3545;
								margin-top: .35rem
							}

							#clinic-registration-form .form-group.has-error .invalid-feedback,
							#clinic-registration-form .form-group.has-error .invalid-feedback:not(:empty) {
								display: block
							}

							#clinic-registration-form .form-group.has-error .form-control,
							#clinic-registration-form .form-group.has-error .form-select.is-invalid {
								border-color: #dc3545
							}

							#clinic-registration-form .form-group.has-error .nice-select {
								border-color: #dc3545
							}

							#clinic-registration-form .specialist-select2 {
								width: 100%
							}

							#clinic-registration-form .select2-container--default .select2-selection--multiple {
								min-height: 48px;
								border: 1px solid #e5e7eb;
								border-radius: .5rem;
								padding: 4px 8px;
								background: #fff
							}

							#clinic-registration-form .select2-container--default .select2-selection--multiple .select2-selection__choice {
								background-color: var(--theme-color, #3b82f6);
								border-color: var(--theme-color, #3b82f6);
								color: #fff;
								border-radius: .35rem;
								padding: 2px 8px
							}

							#clinic-registration-form .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
								color: #fff;
								margin-inline-end: 4px
							}

							#clinic-registration-form .form-group.has-error .select2-container--default .select2-selection--multiple,
							#clinic-registration-form .select2-container--default .select2-selection--multiple.is-invalid {
								border-color: #dc3545
							}

							html[dir="rtl"] #clinic-registration-form .select2-container--default .select2-selection--multiple .select2-selection__rendered {
								text-align: right
							}
							</style>

							<script>
							document.addEventListener('DOMContentLoaded', function() {
								var form = document.getElementById('clinic-registration-form');
								if (!form) return;

								form.querySelectorAll('[data-field-wrap]').forEach(function(wrap) {
									var icon = wrap.querySelector(':scope > i[class*="fa"]');
									if (!icon || icon.closest('.form-field-icons')) {
										return;
									}

									var tray = document.createElement('span');
									tray.className = 'form-field-icons';
									tray.setAttribute('aria-hidden', 'true');

									var errIcon = document.createElement('i');
									errIcon.className = 'fal fa-circle-exclamation form-field-error-icon';

									icon.classList.add('form-field-icon');
									wrap.insertBefore(tray, icon);
									tray.appendChild(errIcon);
									tray.appendChild(icon);
								});

								var regValidation = {
									labels: @json($registrationFieldLabels),
									messages: {
										required: @json(__('main.frontend_validation_required')),
										email: @json(__('main.frontend_validation_email')),
										phone: @json(__('main.frontend_validation_phone')),
										password: @json(__('main.frontend_validation_password')),
										max: @json(__('main.frontend_validation_max')),
										min: @json(__('main.frontend_validation_min')),
										select: @json(__('main.frontend_validation_select')),
										file_required: @json(__('main.frontend_validation_file_required')),
										file_image: @json(__('main.frontend_validation_file_image')),
										file_max: @json(__('main.frontend_validation_file_max'))
									}
								};

								var steps = form.querySelectorAll('.form-step');
								var indicators = form.querySelectorAll('.step-indicator');
								var btnPrev = form.querySelector('.btn-prev');
								var btnNext = form.querySelector('.btn-next');
								var btnSubmit = form.querySelector('.btn-submit');
								var current = 0;
								var emailPattern =
									/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/;
								var imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp',
									'image/jpg'
								];

								function isValidPhone(value) {
									var digits = String(value).replace(/\D/g, '');
									return digits.length >= 8 && digits.length <= 15;
								}

								function labelFor(name) {
									return regValidation.labels[name] || name;
								}

								function formatMessage(template, fieldLabel, extra) {
									var msg = template.replace(/:field/g, fieldLabel);
									if (extra) {
										Object.keys(extra).forEach(function(key) {
											msg = msg.replace(':' + key,
												String(extra[
													key])
												);
										});
									}
									return msg;
								}

								function setFieldError(field, message) {
									var wrap = field.closest('[data-field-wrap]') || field.parentElement;
									var feedback = wrap ? wrap.querySelector('[data-field-error="' + field
										.name + '"]') : null;
									field.classList.add('is-invalid');
									if (wrap) {
										wrap.classList.add('has-error')
									}
									if (field.tagName === 'SELECT' && typeof jQuery !== 'undefined') {
										var nice = field.nextElementSibling;
										if (nice && nice.classList.contains('nice-select')) {
											nice.classList.add('is-invalid')
										}
									}
									if (feedback) {
										feedback.textContent = message;
										feedback.style.display = 'block';
									}
								}

								function clearFieldError(field) {
									var wrap = field.closest('[data-field-wrap]') || field.parentElement;
									var feedback = wrap ? wrap.querySelector('[data-field-error="' + field
										.name + '"]') : null;
									field.classList.remove('is-invalid');
									if (wrap) {
										wrap.classList.remove('has-error')
									}
									if (field.tagName === 'SELECT' && typeof jQuery !== 'undefined') {
										var nice = field.nextElementSibling;
										if (nice && nice.classList.contains('nice-select')) {
											nice.classList.remove('is-invalid')
										}
									}
									if (feedback && !feedback.hasAttribute('data-server-error')) {
										feedback.textContent = '';
										feedback.style.display = '';
									}
								}

								function validateField(field) {
									var rules = (field.getAttribute('data-validate') || '').split('|')
										.filter(Boolean);
									if (!rules.length) {
										return true
									}
									var name = field.name;
									var fieldLabel = labelFor(name);
									var value = field.type === 'file' ? (field.files && field.files[0] ?
										field.files[0] : null) : (field.value || '').trim();
									var isEmpty = field.type === 'file' ? !value : !value.length;

									clearFieldError(field);

									for (var i = 0; i < rules.length; i++) {
										var rule = rules[i];
										var parts = rule.split(':');
										var ruleName = parts[0];
										var ruleVal = parts[1];

										if (ruleName === 'required' && isEmpty) {
											if (field.type === 'file') {
												setFieldError(field, formatMessage(
													regValidation
													.messages
													.file_required,
													fieldLabel));
											} else if (field.tagName === 'SELECT') {
												setFieldError(field, formatMessage(
													regValidation
													.messages
													.select,
													fieldLabel));
											} else {
												setFieldError(field, formatMessage(
													regValidation
													.messages
													.required,
													fieldLabel));
											}
											return false;
										}

										if (isEmpty && ruleName !== 'required') {
											continue
										}

										if (ruleName === 'email' && !emailPattern.test(value)) {
											setFieldError(field, formatMessage(regValidation
												.messages.email,
												fieldLabel));
											return false;
										}

										if (ruleName === 'phone' && !isValidPhone(value)) {
											setFieldError(field, formatMessage(regValidation
												.messages.phone,
												fieldLabel));
											return false;
										}

										if (ruleName === 'password') {
											var minPass = parseInt(ruleVal || '8', 10);
											if (value.length < minPass) {
												setFieldError(field, formatMessage(
													regValidation
													.messages
													.password,
													fieldLabel, {
														min: minPass
													}));
												return false;
											}
										}

										if (ruleName === 'max') {
											if (field.type === 'file' && value) {
												var maxKb = parseInt(ruleVal, 10);
												if (value.size > maxKb * 1024) {
													setFieldError(field, formatMessage(
														regValidation
														.messages
														.file_max,
														fieldLabel, {
															max: Math.round(maxKb /
																1024
																)
														}));
													return false;
												}
											} else if (typeof value === 'string' && value
												.length > parseInt(ruleVal, 10)) {
												setFieldError(field, formatMessage(
													regValidation
													.messages
													.max,
													fieldLabel, {
														max: ruleVal
													}));
												return false;
											}
										}

										if (ruleName === 'min' && typeof value === 'string' && value
											.length < parseInt(ruleVal, 10)) {
											setFieldError(field, formatMessage(regValidation
												.messages.min,
												fieldLabel, {
													min: ruleVal
												}));
											return false;
										}

										if (ruleName === 'image' && field.type === 'file' && value) {
											if (!imageMimeTypes.includes(value.type)) {
												setFieldError(field, formatMessage(
													regValidation
													.messages
													.file_image,
													fieldLabel));
												return false;
											}
										}
									}

									return true;
								}

								function setSpecialistSelectInvalid(select, isInvalid) {
									if (!select) {
										return;
									}
									select.classList.toggle('is-invalid', isInvalid);
									if (typeof jQuery !== 'undefined') {
										jQuery(select).next('.select2-container').find('.select2-selection--multiple')
											.toggleClass('is-invalid', isInvalid);
									}
								}

								function validateSpecialistGroup(wrap) {
									var select = wrap.querySelector('#specialist-select');
									var feedback = wrap.querySelector('[data-field-error="specialist"]');
									var hasSelection = select && select.selectedOptions && select.selectedOptions.length > 0;
									if (hasSelection) {
										wrap.classList.remove('has-error');
										setSpecialistSelectInvalid(select, false);
										if (feedback && !feedback.hasAttribute('data-server-error')) {
											feedback.textContent = '';
											feedback.style.display = '';
										}
										return true;
									}
									wrap.classList.add('has-error');
									setSpecialistSelectInvalid(select, true);
									if (feedback) {
										feedback.textContent = @json(__('main.Please select at least one specialty'));
										feedback.style.display = 'block';
									}
									return false;
								}

								function initSubscriptionSpecialtySelect2() {
									if (typeof jQuery === 'undefined') {
										return;
									}
									var specialistWrap = form.querySelector('[data-validate-group="specialist"]');
									var select = form.querySelector('#specialist-select');
									if (!specialistWrap || !select) {
										return;
									}

									function bootSelect2() {
										var $select = jQuery(select);
										if ($select.data('select2')) {
											return;
										}
										$select.select2({
											placeholder: select.getAttribute('data-placeholder') || '',
											allowClear: true,
											width: '100%',
											dir: @json($subscriptionIsRtl ? 'rtl' : 'ltr'),
											language: @json($subscriptionIsRtl ? 'ar' : 'en'),
											closeOnSelect: false
										});
										$select.on('change.select2-specialist', function() {
											validateSpecialistGroup(specialistWrap);
										});
									}

									if (jQuery.fn.select2) {
										bootSelect2();
										return;
									}

									var script = document.createElement('script');
									script.src = @json(asset('admin/js/select2/select2.full.min.js'));
									script.onload = function() {
										if (@json($subscriptionIsRtl)) {
											var arScript = document.createElement('script');
											arScript.src = @json(asset('build/plugins/select2/js/i18n/ar.js'));
											arScript.onload = bootSelect2;
											document.body.appendChild(arScript);
											return;
										}
										bootSelect2();
									};
									document.body.appendChild(script);
								}

								function validateStep(idx) {
									var fields = steps[idx].querySelectorAll('[data-validate]');
									var valid = true;
									var firstInvalid = null;
									fields.forEach(function(field) {
										if (!validateField(field)) {
											valid = false;
											if (!firstInvalid) {
												firstInvalid = field
											}
										}
									});
									steps[idx].querySelectorAll('[data-validate-group="specialist"]').forEach(function(wrap) {
										if (!validateSpecialistGroup(wrap)) {
											valid = false;
											if (!firstInvalid) {
												firstInvalid = wrap.querySelector('#specialist-select') || wrap;
											}
										}
									});
									if (firstInvalid) {
										firstInvalid.focus({
											preventScroll: true
										});
										firstInvalid.scrollIntoView({
											behavior: 'smooth',
											block: 'center'
										});
									}
									return valid;
								}

								function show(idx) {
									steps.forEach(function(s, i) {
										s.classList.toggle('active', i === idx);
										indicators[i].classList.toggle('active',
											i === idx);
										indicators[i].classList.toggle('completed',
											i < idx);
									});
									btnPrev.style.display = idx === 0 ? 'none' : '';
									btnNext.style.display = idx === steps.length - 1 ? 'none' : '';
									btnSubmit.style.display = idx === steps.length - 1 ? '' : 'none';
									current = idx;
								}

								form.querySelectorAll('[data-validate]').forEach(function(field) {
									var evt = field.type === 'file' || field.tagName ===
										'SELECT' ? 'change' : 'input';
									field.addEventListener(evt, function() {
										validateField(field)
									});
								});

								window.addEventListener('load', initSubscriptionSpecialtySelect2);

								btnNext.addEventListener('click', function() {
									if (validateStep(current) && current < steps.length -
										1) {
										show(current + 1)
									}
								});

								btnPrev.addEventListener('click', function() {
									if (current > 0) {
										show(current - 1)
									}
								});

								form.addEventListener('submit', function(e) {
									var valid = true;
									var firstInvalidStep = null;
									for (var i = 0; i < steps.length; i++) {
										if (!validateStep(i)) {
											valid = false;
											if (firstInvalidStep === null) {
												firstInvalidStep = i
											}
										}
									}
									if (!valid) {
										e.preventDefault();
										show(firstInvalidStep !== null ?
											firstInvalidStep : 0);
									}
								});

								form.querySelectorAll('[data-field-error]').forEach(function(el) {
									if ((el.textContent || '').trim() !== '') {
										el.setAttribute('data-server-error', '1');
										el.style.display = 'block';
										var wrap = el.closest('[data-field-wrap]');
										if (wrap) {
											wrap.classList.add('has-error')
										}
										if (wrap && wrap.getAttribute('data-validate-group') === 'specialist') {
											setSpecialistSelectInvalid(wrap.querySelector('#specialist-select'), true);
										} else {
											var input = wrap ? wrap.querySelector(
													'[name="' + el
													.getAttribute(
														'data-field-error'
														) + '"]') :
												null;
											if (input) {
												input.classList.add('is-invalid')
											}
										}
									}
								});

								show({{max(0, (int) $registrationErrorStep - 1)}});

								function syncCheckoutPackageSelect() {
									var packageSelect = form.querySelector('#checkout-package-select');
									if (!packageSelect || typeof jQuery === 'undefined' || !jQuery.fn
										.niceSelect) {
										return;
									}
									var selectedId = packageSelect.getAttribute('data-selected-package') ||
										new URLSearchParams(window.location.search).get('package');
									if (selectedId) {
										packageSelect.value = String(selectedId);
									}
									var $pkg = jQuery(packageSelect);
									if ($pkg.next('.nice-select').length) {
										$pkg.niceSelect('destroy');
									}
									$pkg.niceSelect();
								}

								if (typeof jQuery !== 'undefined') {
									jQuery(function() {
										syncCheckoutPackageSelect();
										setTimeout(syncCheckoutPackageSelect, 0);
									});
								}

								if (new URLSearchParams(window.location.search).has('package')) {
									var summary = document.getElementById('selected-package-summary');
									if (summary) {
										summary.scrollIntoView({
											behavior: 'smooth',
											block: 'nearest'
										});
									}
								}
							});
							</script>
						</div>

					</div>
				</div>
			</div>