<?php $page = 'loyalty-organizations'; ?>

@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper">
	<div class="content">
		<div
			class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
			<div class="flex-grow-1">
				<h4 class="fw-bold mb-0">
					@lang('main.loyalty_organizations')
					<span
						class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">
						{{ $organizations->total() }}
					</span>
				</h4>
				<p class="text-muted mb-0 mt-1">@lang('main.loyalty_organizations_hint')</p>
			</div>
			<div>
				<button type="button" class="btn btn-primary" data-bs-toggle="modal"
					data-bs-target="#addOrganizationModal">
					@lang('main.loyalty_add_organization')
				</button>
			</div>
		</div>

		@if($errors->any() && old('name'))
		<div class="alert alert-danger">
			<ul class="mb-0">
				@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif

		<div class="card">
			<div class="card-body">
				<form method="GET" class="row g-3 mb-4">
					<div class="col-md-4">
						<input type="text" name="name" value="{{ request('name') }}"
							class="form-control"
							placeholder="@lang('main.search_by_name')">
					</div>
					<div class="col-md-3">
						<select name="app_type" class="form-control">
							<option value="">@lang('admin.app_type')</option>
							@foreach($appTypes as $type)
							<option value="{{ $type->id }}"
								{{ (string) request('app_type') === (string) $type->id ? 'selected' : '' }}>
								{{ app()->getLocale() === 'ar' ? $type->name_ar : $type->name_en }}
							</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-3">
						<select name="points_enabled" class="form-control">
							<option value="">
								@lang('main.loyalty_points_status')
							</option>
							<option value="1"
								{{ request('points_enabled') === '1' ? 'selected' : '' }}>
								@lang('main.enabled')</option>
							<option value="0"
								{{ request('points_enabled') === '0' ? 'selected' : '' }}>
								@lang('main.disabled')</option>
						</select>
					</div>
					<div class="col-md-2">
						<button class="btn btn-primary w-100"
							type="submit">@lang('admin.Filter')</button>
					</div>
				</form>

				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>@lang('admin.name')</th>
								<th>@lang('admin.app_type')</th>
								<th>@lang('admin.email')</th>
								<th>@lang('main.loyalty_points_status')
								</th>
								<th>@lang('main.clinic_enabled_modules')
								</th>
								<th>@lang('main.loyalty_category')</th>
								<th>@lang('main.loyalty_coupons')</th>
								<th>@lang('main.loyalty_redemptions')
								</th>
								<th>@lang('admin.Actions')</th>
							</tr>
						</thead>
						<tbody>
							@forelse($organizations as $organization)
							@php
							$displayModules =
							$organization->displayModuleKeys();
							$locale = app()->getLocale();
							@endphp
							<tr>
								<td>{{ $organization->id }}</td>
								<td>{{ $organization->name }}</td>
								<td>
									{{ $locale === 'ar'
                                            ? optional($organization->appType)->name_ar
                                            : optional($organization->appType)->name_en }}
								</td>
								<td>{{ $organization->email }}</td>
								<td>
									<div class="d-inline-block">
										<div
											class="form-check form-switch ps-0 mb-0">
											<input type="checkbox"
												id="loyalty{{ $organization->id }}"
												class="form-check-input m-0 loyalty-toggle"
												{{ (int) $organization->points_enabled === 1 ? 'checked' : '' }}
												onchange="toggleLoyaltyPoints({{ $organization->id }}, this)">
										</div>
									</div>
								</td>
								<td>
									@if($displayModules === [])
									<span
										class="text-muted">-</span>
									@else
									<div
										class="d-flex flex-wrap gap-1">
										@foreach($displayModules as $moduleKey)
										@php $def =
										$moduleDefinitions[$moduleKey]
										?? null; @endphp
										<span
											class="badge bg-light text-dark border">
											{{ $def ? ($locale === 'ar' ? $def['label_ar'] : $def['label_en']) : $moduleKey }}
										</span>
										@endforeach
									</div>
									@endif
								</td>
								<td>{{ $organization->points_category ?: '-' }}
								</td>
								<td>{{ $organization->loyalty_coupons_count }}
								</td>
								<td>{{ $organization->loyalty_redemptions_count }}
								</td>
								<td>
									<div
										class="d-flex flex-wrap gap-1">
										<button type="button"
											class="btn btn-sm btn-outline-primary"
											data-bs-toggle="modal"
											data-bs-target="#editLoyalty{{ $organization->id }}">
											@lang('main.edit_loyalty')
										</button>
										<button type="button"
											class="btn btn-sm btn-outline-secondary"
											data-bs-toggle="modal"
											data-bs-target="#editOrganization{{ $organization->id }}">
											@lang('main.edit_organization')
										</button>
									</div>
								</td>
							</tr>
							@empty
							<tr>
								<td colspan="10"
									class="text-center text-muted">
									@lang('main.no_data')</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>

				{{ $organizations->links() }}
			</div>
		</div>
	</div>
</div>

@foreach($organizations as $organization)
@php
$selectedModules = $organization->selectedModuleKeys();
$locale = app()->getLocale();
@endphp
<div class="modal fade" id="editLoyalty{{ $organization->id }}" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form method="POST"
				action="{{ route('loyalty-organizations.update', $organization->id) }}"
				class="org-modules-form">
				@csrf
				@method('PUT')
				<div class="modal-header">
					<h5 class="modal-title">@lang('main.edit_loyalty') —
						{{ $organization->name }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"
						aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label
							class="form-label">@lang('main.loyalty_points_status')</label>
						<select name="points_enabled"
							class="form-control points-enabled-select">
							<option value="1"
								{{ $organization->points_enabled ? 'selected' : '' }}>
								@lang('main.enabled')</option>
							<option value="0"
								{{ ! $organization->points_enabled ? 'selected' : '' }}>
								@lang('main.disabled')</option>
						</select>
					</div>

					<div class="mb-3">
						<label
							class="form-label">@lang('main.clinic_enabled_modules')</label>
						<div class="row g-2">
							@foreach($moduleDefinitions as $moduleKey => $definition)
							<div class="col-md-6">
								<div
									class="form-check border rounded px-3 py-2 mb-0">
									<input class="form-check-input module-checkbox"
										type="checkbox"
										name="enabled_modules[]"
										value="{{ $moduleKey }}"
										id="module_{{ $organization->id }}_{{ $moduleKey }}"
										data-module="{{ $moduleKey }}"
										{{ in_array($moduleKey, $selectedModules, true) ? 'checked' : '' }}>
									<label class="form-check-label"
										for="module_{{ $organization->id }}_{{ $moduleKey }}">
										{{ $locale === 'ar' ? $definition['label_ar'] : $definition['label_en'] }}
									</label>
								</div>
							</div>
							@endforeach
						</div>
						@if($errors->has('enabled_modules'))
						<div class="text-danger small mt-1">
							{{ $errors->first('enabled_modules') }}</div>
						@endif
					</div>

					<div class="mb-0">
						<label
							class="form-label">@lang('main.loyalty_category')</label>
						<input type="text" name="points_category"
							class="form-control"
							value="{{ $organization->points_category }}"
							placeholder="clinic / hospital / lab">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit"
						class="btn btn-primary">@lang('admin.save')</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endforeach

@foreach($organizations as $organization)
<div class="modal fade" id="editOrganization{{ $organization->id }}" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">@lang('main.edit_organization') —
					{{ $organization->name }}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"
					aria-label="Close"></button>
			</div>
			<form method="POST"
				action="{{ route('loyalty-organizations.update-organization', $organization->id) }}"
				class="org-modules-form d-flex flex-column overflow-hidden"
				enctype="multipart/form-data" data-app-type="{{ $organization->app_type }}">
				@csrf
				@method('PUT')
				<div class="modal-body organization-modal-body">
					@include('main_admin.loyalty.partials.organization-form-fields', [
					'organization' => $organization,
					'mode' => 'edit',
					'includeLoyalty' => false,
					'includeMap' => true,
					'formKey' => 'edit_' . $organization->id,
					])
				</div>
				<div class="modal-footer flex-shrink-0">
					<button type="submit"
						class="btn btn-primary">@lang('admin.save')</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endforeach

<div class="modal fade" id="addOrganizationModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">@lang('main.loyalty_add_organization')</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"
					aria-label="Close"></button>
			</div>
			<form method="POST" action="{{ route('loyalty-organizations.store') }}"
				class="org-modules-form d-flex flex-column overflow-hidden"
				enctype="multipart/form-data">
				@csrf
				<div class="modal-body organization-modal-body">
					@include('main_admin.loyalty.partials.organization-form-fields', [
					'mode' => 'create',
					'includeLoyalty' => true,
					'includeMap' => true,
					'formKey' => 'new',
					])
				</div>
				<div class="modal-footer flex-shrink-0">
					<button type="submit"
						class="btn btn-primary">@lang('admin.save')</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
#addOrganizationModal .modal-dialog,
.modal[id^="editOrganization"] .modal-dialog {
	max-width: min(1140px, calc(100vw - 2rem));
	margin: 1rem auto;
}

#addOrganizationModal .modal-content,
.modal[id^="editOrganization"] .modal-content {
	max-height: calc(100vh - 2rem);
}

#addOrganizationModal .organization-modal-body,
.modal[id^="editOrganization"] .organization-modal-body {
	max-height: calc(100vh - 12rem);
	overflow-y: auto;
	overflow-x: hidden;
}

.organization-map {
	height: 320px;
	width: 100%;
	border-radius: 0.5rem;
	border: 1px solid #dee2e6;
	background: #f8f9fa;
}

.pac-container {
	z-index: 20000 !important;
}
</style>
@endpush

@push('scripts')
<script>
function toggleLoyaltyPoints(id, checkbox) {
	var status = checkbox.checked ? 1 : 0;

	$.ajax({
		url: "{{ url('admin/loyalty-organizations') }}/" + id + "/status/" + status,
		type: 'GET',
		dataType: 'json',
		success: function(data) {
			if (typeof showToast === 'function') {
				showToast(data.message ||
					"{{ trans('messages.updated') }}"
					);
			}
		},
		error: function() {
			checkbox.checked = !checkbox.checked;
			if (typeof showToast === 'function') {
				showToast("{{ trans('messages.something_went_wrong') }}",
					'danger');
			}
		}
	});
}

function syncPointsModule($form) {
	var pointsEnabled = parseInt($form.find('.points-enabled-select').val(), 10) === 1;
	var $pointsCheckbox = $form.find('.module-checkbox[data-module="points"]');

	if ($pointsCheckbox.length) {
		$pointsCheckbox.prop('checked', pointsEnabled);
	}
}

function syncOrganizationFields($scope) {
	var $rootScope = $($scope || document);
	$rootScope.find('.org-modules-form, #addOrganizationModal').each(function() {
		var $form = $(this);
		var appType = String($form.find('.organization-app-type').val() || $form.data(
			'app-type') || '1');

		$form.find('.org-field').show();
		$form.find('.org-field input, .org-field select, .org-field textarea')
			.not('[type=hidden]')
			.prop('disabled', false);

		var showSpecialties = appType === '1' || appType === '7';
		$form.find('.org-specialties-field').toggle(showSpecialties);
		$form.find('.org-specialties-field select').prop('disabled', !showSpecialties);

		var showParent = appType === '7';
		$form.find('[name="parent_id"]').closest('.org-field').toggle(showParent);
		$form.find('[name="parent_id"]').prop('disabled', !showParent);
	});
}

var organizationMaps = {};
var organizationMapsLoading = false;

function setOrganizationLatLng($form, location) {
	$form.find('.organization-lat').val(location.lat());
	$form.find('.organization-lng').val(location.lng());
}

function setOrganizationMarker(mapCtx, location) {
	if (!mapCtx || !mapCtx.map || !mapCtx.marker) {
		return;
	}
	mapCtx.marker.setPosition(location);
	mapCtx.map.panTo(location);
	setOrganizationLatLng(mapCtx.form, location);
}

function initOrganizationMapForForm($form) {
	var mapElement = $form.find('.organization-map').get(0);
	if (!mapElement || !(window.google && window.google.maps)) {
		return;
	}
	var mapId = mapElement.id;
	if (organizationMaps[mapId]) {
		return;
	}

	var defaultLocation = {
		lat: 21.485811,
		lng: 39.192505
	};
	var savedLat = parseFloat($form.find('.organization-lat').val());
	var savedLng = parseFloat($form.find('.organization-lng').val());
	if (!isNaN(savedLat) && !isNaN(savedLng)) {
		defaultLocation = {
			lat: savedLat,
			lng: savedLng
		};
	}

	var map = new google.maps.Map(mapElement, {
		zoom: 12,
		center: defaultLocation,
		mapTypeControl: true,
		streetViewControl: true,
		fullscreenControl: true
	});

	var marker = new google.maps.Marker({
		position: defaultLocation,
		map: map,
		draggable: true,
		animation: google.maps.Animation.DROP
	});

	var addressInput = $form.find('.organization-address').get(0);
	if (addressInput) {
		var autocomplete = new google.maps.places.Autocomplete(addressInput, {
			types: ['geocode']
		});
		autocomplete.addListener('place_changed', function() {
			var place = autocomplete.getPlace();
			if (place.geometry) {
				setOrganizationMarker(organizationMaps[mapId], place
					.geometry.location);
			}
		});
	}

	map.addListener('click', function(event) {
		setOrganizationMarker(organizationMaps[mapId], event.latLng);
	});

	marker.addListener('dragend', function(event) {
		setOrganizationLatLng($form, event.latLng);
	});

	organizationMaps[mapId] = {
		map: map,
		marker: marker,
		form: $form
	};
	setOrganizationLatLng($form, marker.getPosition());
}

function loadOrganizationMaps(callback) {
	if (window.google && window.google.maps) {
		callback();
		return;
	}
	if (organizationMapsLoading) {
		return;
	}
	organizationMapsLoading = true;
	var script = document.createElement('script');
	script.src =
		'https://maps.googleapis.com/maps/api/js?key=AIzaSyAPf96eskAPXvkyDLPyYhxSCAKIziCUG_E&libraries=places';
	script.async = true;
	script.defer = true;
	script.onload = function() {
		organizationMapsLoading = false;
		callback();
	};
	document.head.appendChild(script);
}

function refreshOrganizationMap($form) {
	if (!$form || !$form.length || !$form.find('.organization-map').length) {
		return;
	}
	loadOrganizationMaps(function() {
		initOrganizationMapForForm($form);
		var mapId = $form.find('.organization-map').attr('id');
		var mapCtx = organizationMaps[mapId];
		if (mapCtx && mapCtx.map && mapCtx.marker) {
			google.maps.event.trigger(mapCtx.map, 'resize');
			mapCtx.map.setCenter(mapCtx.marker.getPosition());
		}
	});
}

$(document).on('change', '.organization-app-type', function() {
	var $form = $(this).closest('form');
	syncOrganizationFields($form);
	refreshOrganizationMap($form);
});

$(document).on('change', '.points-enabled-select', function() {
	syncPointsModule($(this).closest('.org-modules-form'));
});

$(document).on('change', '.module-checkbox[data-module="points"]', function() {
	var $form = $(this).closest('.org-modules-form');
	$form.find('.points-enabled-select').val(this.checked ? '1' : '0');
});

$(document).on('submit', '.org-modules-form', function(e) {
	var $form = $(this);
	if (!$form.find('.module-checkbox').length) {
		return;
	}
	var checkedCount = $form.find('.module-checkbox:checked').length;
	if (checkedCount === 0) {
		e.preventDefault();
		if (typeof showToast === 'function') {
			showToast("{{ trans('main.clinic_modules_required') }}", 'danger');
		}
	}
});

$('#addOrganizationModal').on('shown.bs.modal', function() {
	var $form = $(this).find('.org-modules-form');
	if ($form.length) {
		syncPointsModule($form);
	}
	syncOrganizationFields(this);
	refreshOrganizationMap($form);
});

$(document).on('shown.bs.modal', '.modal[id^="editOrganization"]', function() {
	var $form = $(this).find('.org-modules-form');
	syncOrganizationFields(this);
	refreshOrganizationMap($form);
});

$(document).on('shown.bs.modal', '.modal[id^="editLoyalty"]', function() {
	var $form = $(this).find('.org-modules-form');
	if ($form.length) {
		syncPointsModule($form);
	}
});

$('.org-modules-form').each(function() {
	syncPointsModule($(this));
});

syncOrganizationFields();
</script>
@endpush
