@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:10px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<a href="{{ route('cms.pages.index') }}" class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i> {{ __('cms.back') }}
					</a>
				</div>
				<h4 class="page-title">{{ __('cms.create_page') }}</h4>
			</div>
		</div>
	</div>

	<form action="{{ route('cms.pages.store') }}" method="POST">
		@csrf

		<div class="row">
			<!-- Main Content -->
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('cms.translations') }}
						</h5>
					</div>
					<div class="card-body">
						<!-- Language Tabs -->
						<ul class="nav nav-tabs" id="languageTabs" role="tablist">
							@foreach($languages as $index => $lang)
							<li class="nav-item" role="presentation">
								<button class="nav-link {{ $index === 0 ? 'active' : '' }}"
									id="tab-{{ $lang->code }}"
									data-bs-toggle="tab"
									data-bs-target="#content-{{ $lang->code }}"
									type="button" role="tab">
									{{ $lang->flag ?? '' }}
									{{ $lang->name }}
									@if($lang->is_default)
									<span
										class="badge bg-success ms-1">{{ __('cms.default') }}</span>
									@endif
								</button>
							</li>
							@endforeach
						</ul>

						<div class="tab-content pt-3" id="languageTabsContent">
							@foreach($languages as $index => $lang)
							<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
								id="content-{{ $lang->code }}"
								role="tabpanel">

								<div class="mb-3">
									<label class="form-label">
										{{ __('cms.title') }}
										({{ $lang->name }})
										<span
											class="text-danger">*</span>
									</label>
									<input type="text"
										class="form-control @error('translations.'.$lang->code.'.title') is-invalid @enderror"
										name="translations[{{ $lang->code }}][title]"
										value="{{ old('translations.'.$lang->code.'.title') }}"
										dir="{{ $lang->direction }}"
										required>
									@error('translations.'.$lang->code.'.title')
									<div class="invalid-feedback">
										{{ $message }}</div>
									@enderror
								</div>

								@include('components.rich-text-editor',
								[
								'inputId' => 'page_meta_description_' .
								$lang->code,
								'inputName' => 'translations[' .
								$lang->code . '][meta_description]',
								'label' => __('cms.meta_description') .
								' ('
								. $lang->name . ')',
								'value' =>
								old('translations.'.$lang->code.'.meta_description'),
								'direction' => $lang->direction,
								'placeholder' =>
								__('cms.enter_meta_description')
								])

								<div class="mb-3">
									<label class="form-label">{{ __('cms.meta_keywords') }}
										({{ $lang->name }})</label>
									<input type="text"
										class="form-control"
										name="translations[{{ $lang->code }}][meta_keywords]"
										value="{{ old('translations.'.$lang->code.'.meta_keywords') }}"
										dir="{{ $lang->direction }}"
										placeholder="{{ __('cms.enter_meta_keywords') }}">
								</div>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>

			<!-- Sidebar -->
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('cms.page_settings') }}
						</h5>
					</div>
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label">{{ __('cms.internal_name') }}
								<span
									class="text-danger">*</span></label>
							<input type="text"
								class="form-control @error('name') is-invalid @enderror"
								name="name" value="{{ old('name') }}"
								required>
							<small
								class="text-muted">{{ __('cms.for_admin_identification_only') }}</small>
							@error('name')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-3">
							<label class="form-label">{{ __('cms.slug') }}
								<span
									class="text-danger">*</span></label>
							<input type="text"
								class="form-control @error('slug') is-invalid @enderror"
								name="slug" value="{{ old('slug') }}"
								required>
							<small
								class="text-muted">{{ __('cms.url_friendly_identifier') }}</small>
							@error('slug')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="mb-3">
							<label
								class="form-label">{{ __('cms.order') }}</label>
							<input type="number" class="form-control"
								name="order"
								value="{{ old('order', 0) }}">
						</div>

						<div class="mb-3">
							<div class="form-check form-switch">
								<input type="checkbox"
									class="form-check-input"
									name="is_active"
									id="is_active" value="1"
									{{ old('is_active', true) ? 'checked' : '' }}>
								<label class="form-check-label"
									for="is_active">{{ __('cms.active') }}</label>
							</div>
						</div>

						<div class="d-grid">
							<button type="submit" class="btn btn-primary">
								<i class="mdi mdi-content-save"></i>
								{{ __('cms.save_page') }}
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
	// Auto-generate slug from name
	$('input[name="name"]').on('blur', function() {
		var slug = $('input[name="slug"]');
		if (!slug.val()) {
			var name = $(this).val();
			slug.val(name.toLowerCase()
				.replace(/[^\w\s-]/g, '')
				.replace(/\s+/g, '-')
				.replace(/-+/g, '-')
				.trim());
		}
	});
});
</script>
@endpush
