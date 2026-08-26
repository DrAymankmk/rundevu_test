@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:10px">
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<a href="{{ route('blog.categories.index') }}" class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i> {{ __('blog.back') }}
					</a>
				</div>
				<h4 class="page-title">{{ __('blog.add_category') }}</h4>
			</div>
		</div>
	</div>

	@if($languages->isEmpty())
	<div class="alert alert-warning">{{ __('blog.no_active_cms_languages') }}</div>
	@endif

	<form action="{{ route('blog.categories.store') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="row">
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('blog.translations') }}</h5>
					</div>
					<div class="card-body">
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
									<span class="badge bg-success ms-1">{{ __('blog.default') }}</span>
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
										{{ __('blog.title') }} ({{ $lang->name }})
										<span class="text-danger">*</span>
									</label>
									<input type="text"
										class="form-control @error('translations.'.$lang->code.'.title') is-invalid @enderror"
										name="translations[{{ $lang->code }}][title]"
										value="{{ old('translations.'.$lang->code.'.title') }}"
										dir="{{ $lang->direction }}"
										required>
									@error('translations.'.$lang->code.'.title')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">{{ __('blog.description') }} ({{ $lang->name }})</label>
									<textarea class="form-control @error('translations.'.$lang->code.'.description') is-invalid @enderror"
										name="translations[{{ $lang->code }}][description]"
										rows="4"
										dir="{{ $lang->direction }}">{{ old('translations.'.$lang->code.'.description') }}</textarea>
									@error('translations.'.$lang->code.'.description')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">{{ __('blog.settings') }}</h5>
					</div>
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label">{{ __('blog.internal_name') }} <span class="text-danger">*</span></label>
							<input type="text" class="form-control @error('name') is-invalid @enderror"
								name="name" value="{{ old('name') }}" required>
							<small class="text-muted">{{ __('blog.for_admin_identification_only') }}</small>
							@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
						</div>

						<div class="mb-3">
							<label class="form-label">{{ __('blog.slug') }}</label>
							<input type="text" class="form-control @error('slug') is-invalid @enderror"
								name="slug" value="{{ old('slug') }}">
							<small class="text-muted">{{ __('blog.slug_hint') }}</small>
							@error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
						</div>

						@include('components.image-upload', [
							'inputId' => 'category_image',
							'inputName' => 'image',
							'collection' => 'image',
							'label' => __('blog.image'),
						])

						<div class="mb-3">
							<div class="form-check form-switch">
								<input type="checkbox" class="form-check-input" name="is_active"
									id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
								<label class="form-check-label" for="is_active">{{ __('blog.active') }}</label>
							</div>
						</div>

						<div class="d-grid">
							<button type="submit" class="btn btn-primary" {{ $languages->isEmpty() ? 'disabled' : '' }}>
								<i class="mdi mdi-content-save"></i> {{ __('blog.save_category') }}
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
