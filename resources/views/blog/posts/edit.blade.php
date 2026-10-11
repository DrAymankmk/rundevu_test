@extends('layout_new.mainlayout')

@section('content')
@php
	$selectedCategoryIds = old('category_ids', $post->categories->pluck('id')->all());
	$publishDateValue = old('publish_date', optional($post->publish_date)->format('Y-m-d\TH:i'));
@endphp
<div class="page-wrapper" style="padding:10px">
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<a href="{{ route('blog.posts.index') }}" class="btn btn-secondary">
						<i class="mdi mdi-arrow-left"></i> {{ __('blog.back') }}
					</a>
				</div>
				<h4 class="page-title">{{ __('blog.edit_post') }}: {{ $post->name }}</h4>
			</div>
		</div>
	</div>

	@if($languages->isEmpty())
	<div class="alert alert-warning">{{ __('blog.no_active_cms_languages') }}</div>
	@endif

	<form action="{{ route('blog.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		@method('PUT')
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
							@php
								$translation = $post->translations->where('locale', $lang->code)->first();
								$tagsValue = old(
									'translations.'.$lang->code.'.tags',
									is_array($translation->tags ?? null) ? implode(', ', $translation->tags) : ''
								);
							@endphp
							<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
								id="content-{{ $lang->code }}"
								role="tabpanel">
								<div class="mb-3">
									<label class="form-label">
										{{ __('blog.title') }} ({{ $lang->name }})
										<span class="text-danger">*</span>
									</label>
									<input type="text"
										class="form-control js-blog-title @error('translations.'.$lang->code.'.title') is-invalid @enderror"
										name="translations[{{ $lang->code }}][title]"
										value="{{ old('translations.'.$lang->code.'.title', $translation->title ?? '') }}"
										dir="{{ $lang->direction }}"
										data-locale="{{ $lang->code }}"
										required>
									@error('translations.'.$lang->code.'.title')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">{{ __('blog.slug') }} ({{ $lang->name }})</label>
									<input type="text"
										class="form-control js-blog-slug @error('translations.'.$lang->code.'.slug') is-invalid @enderror"
										name="translations[{{ $lang->code }}][slug]"
										value="{{ old('translations.'.$lang->code.'.slug', $translation->slug ?? '') }}"
										dir="{{ $lang->direction }}"
										data-locale="{{ $lang->code }}">
									<small class="text-muted">{{ __('blog.slug_hint') }}</small>
									@error('translations.'.$lang->code.'.slug')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">
										{{ __('blog.summary') }} ({{ $lang->name }})
										<span class="text-danger">*</span>
									</label>
									<textarea class="form-control @error('translations.'.$lang->code.'.summary') is-invalid @enderror"
										name="translations[{{ $lang->code }}][summary]"
										rows="3"
										dir="{{ $lang->direction }}"
										required>{{ old('translations.'.$lang->code.'.summary', $translation->summary ?? '') }}</textarea>
									@error('translations.'.$lang->code.'.summary')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								@include('components.rich-text-editor', [
									'inputId' => 'blog_post_content_' . $lang->code,
									'inputName' => 'translations[' . $lang->code . '][content]',
									'label' => __('blog.content') . ' (' . $lang->name . ')',
									'value' => old('translations.'.$lang->code.'.content', $translation->content ?? ''),
									'direction' => $lang->direction,
									'tabPaneId' => 'content-' . $lang->code,
								])
								@error('translations.'.$lang->code.'.content')
								<div class="text-danger small mt-1">{{ $message }}</div>
								@enderror

								<div class="mb-3 mt-3">
									<label class="form-label">{{ __('blog.tags') }} ({{ $lang->name }})</label>
									<input type="text"
										class="form-control @error('translations.'.$lang->code.'.tags') is-invalid @enderror"
										name="translations[{{ $lang->code }}][tags]"
										value="{{ $tagsValue }}"
										dir="{{ $lang->direction }}"
										placeholder="{{ __('blog.tags_hint') }}">
									<small class="text-muted">{{ __('blog.tags_hint') }}</small>
									@error('translations.'.$lang->code.'.tags')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								@php
									$localeCollection = 'image_' . $lang->code;
									$localeImage = $post->getFirstMedia($localeCollection);
									if (!$localeImage && $lang->is_default) {
										$localeImage = $post->getFirstMedia('image');
									}
									$localeImageUrl = $localeImage
										? ($localeImage->hasGeneratedConversion('thumb') ? $localeImage->getUrl('thumb') : $localeImage->getUrl())
										: null;
									$localeImageCollection = $localeImage?->collection_name ?: $localeCollection;
								@endphp
								@include('components.image-upload', [
									'inputId' => 'post_image_' . $lang->code,
									'inputName' => 'translations[' . $lang->code . '][image]',
									'collection' => $localeImageCollection,
									'label' => __('blog.image') . ' (' . $lang->name . ')',
									'existingImage' => $localeImageUrl,
									'existingAlt' => \App\Support\Cms\CmsGalleryMedia::alt($localeImage),
									'model' => $post,
								])
								@if($localeImage)
								<div class="mb-3 form-check">
									<input type="checkbox" class="form-check-input"
										name="translations[{{ $lang->code }}][remove_image]"
										id="remove_image_{{ $lang->code }}" value="1">
									<label class="form-check-label" for="remove_image_{{ $lang->code }}">
										{{ __('blog.remove_image') }} ({{ $lang->name }})
									</label>
								</div>
								@endif
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
								name="name" value="{{ old('name', $post->name) }}" required>
							<small class="text-muted">{{ __('blog.for_admin_identification_only') }}</small>
							@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
						</div>

						<div class="mb-3">
							<label class="form-label">{{ __('blog.categories') }}</label>
							<select name="category_ids[]" class="form-control select2 @error('category_ids') is-invalid @enderror" multiple>
								@foreach($categories as $category)
								<option value="{{ $category->id }}"
									{{ collect($selectedCategoryIds)->contains($category->id) ? 'selected' : '' }}>
									{{ $category->getTranslatedAttribute('title') ?: $category->name }}
								</option>
								@endforeach
							</select>
							@error('category_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
						</div>

						<div class="mb-3">
							<label class="form-label">{{ __('blog.publish_date') }}</label>
							<input type="datetime-local" class="form-control @error('publish_date') is-invalid @enderror"
								name="publish_date" value="{{ $publishDateValue }}">
							@error('publish_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
						</div>

						<div class="mb-3">
							<div class="form-check form-switch">
								<input type="checkbox" class="form-check-input" name="is_active"
									id="is_active" value="1" {{ old('is_active', $post->is_active) ? 'checked' : '' }}>
								<label class="form-check-label" for="is_active">{{ __('blog.active') }}</label>
							</div>
						</div>

						<div class="d-grid">
							<button type="submit" class="btn btn-primary" {{ $languages->isEmpty() ? 'disabled' : '' }}>
								<i class="mdi mdi-content-save"></i> {{ __('blog.update_post') }}
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('components.seo-form', [
			'languages' => $languages,
			'seo' => $post->seoMeta,
		])
	</form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
	if ($.fn.select2) {
		$('.select2').select2({ width: '100%', placeholder: '{{ __("blog.select_categories") }}' });
	}

	function slugify(text) {
		return String(text || '')
			.trim()
			.toLowerCase()
			.replace(/[^\p{L}\p{N}\s-]+/gu, '')
			.replace(/[\s_]+/g, '-')
			.replace(/-+/g, '-')
			.replace(/^-+|-+$/g, '');
	}

	$('.js-blog-title').on('blur', function() {
		var locale = $(this).data('locale');
		var slug = $('.js-blog-slug[data-locale="' + locale + '"]');
		if (slug.length && !slug.val()) {
			slug.val(slugify($(this).val()));
		}
	});
});
</script>
@endpush
