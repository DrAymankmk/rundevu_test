@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\CmsLanguage> $languages */
        $section = $section ?? null;
        $sectionTypeCanonical = ['default', 'hero', 'features', 'about-us',
        'services', 'why-choose-us', 'download-app', 'contact', 'faq', 'plans',
        'values'];
        if (($pageSlug ?? '') === 'subscription') {
        $sectionTypeCanonical =
        array_values(array_unique(array_merge(['subscription',
        'checkout'], $sectionTypeCanonical)));
        }
        $typeLabel = static function ($value) {
        $key = 'cms.' . str_replace('-', '_', strtolower((string) $value));
        $translated = __($key);
        if ($translated !== $key) {
        return $translated;
        }

        return ucfirst(str_replace(['-', '_'], ' ', (string) $value));
        };
        $layoutLabel = static function ($value) {
        $normalized = strtolower(trim((string) $value));
        if ($normalized === 'style_1') {
        return __('cms.style_1');
        }
        if ($normalized === 'style_2') {
        return __('cms.style_2');
        }

        return __('cms.default_layout');
        };
        $tp = \App\Models\CmsSection::normalizeType(old('sections.'.$sidx.'.type',
        $section?->type ?? 'default'));
        $sectionTypeOptions = $sectionTypeCanonical;
        if (! in_array($tp, $sectionTypeOptions, true)) {
        $sectionTypeOptions[] = $tp;
        }
        $currentLayout = \App\Models\CmsSection::normalizeLayout(
        old('sections.'.$sidx.'.section_layout', $section?->section_layout ?? null),
        $tp
        );
        $layoutOptions = \App\Models\CmsSection::layoutOptionsFor($tp);
        $currentPreview = \App\Models\CmsSection::previewFor($tp, $currentLayout);
        $sectionDisplayName = old("sections.{$sidx}.name", $section?->name ?? __('cms.section'));
@endphp
<div class="card mb-3 section-card border-primary" data-role="section-card">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
            <button type="button"
                    class="btn btn-sm btn-light border px-1 py-0 flex-shrink-0"
                    data-bs-toggle="collapse"
                    data-bs-target="#section-collapse-{{ $sidx }}" aria-expanded="true"
                    aria-controls="section-collapse-{{ $sidx }}"
                    data-role="section-collapse-toggle"
                    title="{{ __('cms.toggle_section') }}">
                <i class="mdi mdi-chevron-down"></i>
            </button>
            <strong class="text-truncate"
                    data-role="section-name-label">{{ $sectionDisplayName }}</strong>
            <span class="badge bg-light text-dark border"
                  data-role="section-type-label">{{ $typeLabel($tp) }}</span>
            <span class="badge bg-light text-primary border"
                  data-role="section-layout-label">{{ $layoutLabel($currentLayout) }}</span>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <button type="button" class="btn btn-sm btn-outline-info"
                    data-role="section-layout-guide">
                <i class="mdi mdi-image-search-outline"></i>
                {{ __('cms.section_layout_guide') }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger"
                    data-role="remove-section">{{ __('cms.remove_section') }}</button>
        </div>
    </div>
    <div id="section-collapse-{{ $sidx }}" class="collapse show" data-role="section-collapse">
        <div class="card-body">
            @if($section)
                <input type="hidden" name="sections[{{ $sidx }}][id]"
                       value="{{ $section->id }}">
            @endif
            <input type="hidden" name="sections[{{ $sidx }}][is_active]" value="0">
            <div class="row g-3 mb-3 align-items-stretch">
                <div class="col-lg-8">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('cms.internal_name') }}
                                *</label>
                            <input type="text" class="form-control"
                                   data-role="section-name-input"
                                   name="sections[{{ $sidx }}][name]"
                                   value="{{ old("sections.{$sidx}.name", $section?->name ?? '') }}"
                                   placeholder="{{ __('cms.section_name_placeholder') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('cms.type') }}
                                *</label>
                            <select class="form-select"
                                    data-role="section-type-select"
                                    name="sections[{{ $sidx }}][type]">
                                @foreach($sectionTypeOptions as $opt)
                                    <option value="{{ $opt }}"
                                        {{ $tp === $opt ? 'selected' : '' }}>
                                        {{ $typeLabel($opt) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label
                                class="form-label">{{ __('cms.section_layout') }}</label>
                            <select class="form-select"
                                    data-role="section-layout-select"
                                    name="sections[{{ $sidx }}][section_layout]">
                                @foreach($layoutOptions as $layoutKey => $layoutMeta)
                                    <option value="{{ $layoutKey }}"
                                            data-preview="{{ $layoutMeta['preview'] ?? '' }}"
                                        {{ $currentLayout === $layoutKey ? 'selected' : '' }}>
                                        {{ $layoutLabel($layoutKey) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label
                                class="form-label">{{ __('cms.order') }}</label>
                            <input type="number"
                                   class="form-control"
                                   name="sections[{{ $sidx }}][order]"
                                   value="{{ old('sections.'.$sidx.'.order', $section?->order ?? 0) }}">
                        </div>
                        <!-- <div class="col-md-8">
								<label
									class="form-label">{{ __('cms.template') }}</label>
								<input type="text" class="form-control"
									name="sections[{{ $sidx }}][template]"
									value="{{ old('sections.'.$sidx.'.template', $section?->template ?? '') }}"
									placeholder="{{ __('cms.enter_template') }}">
							</div> -->
                        <div class="col-md-4">
                            @php $sectionActiveId =
								'section-active-'.$sidx; @endphp
                            <div
                                class="d-flex align-items-center justify-content-between gap-2 mt-4 pt-md-2">
                                <label class="form-label mb-0"
                                       for="{{ $sectionActiveId }}">{{ __('cms.active') }}</label>
                                <div
                                    class="form-check form-switch m-0">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           role="switch"
                                           id="{{ $sectionActiveId }}"
                                           name="sections[{{ $sidx }}][is_active]"
                                           value="1"
                                        {{ old('sections.'.$sidx.'.is_active', $section?->is_active ?? true) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="small text-muted mt-2">
                        {{ __('cms.section_builder_hint') }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div
                        class="card border-0 bg-light h-100 builder-layout-preview">
                        <div class="card-body p-3">
                            <div
                                class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                <div>
                                    <div
                                        class="fw-semibold small">
                                        {{ __('cms.layout_preview') }}
                                    </div>
                                    <div class="text-muted small"
                                         data-role="section-preview-type">
                                        {{ $typeLabel($tp) }}
                                    </div>
                                </div>
                                <span class="badge bg-white text-primary border"
                                      data-role="section-layout-pill">{{ $layoutLabel($currentLayout) }}</span>
                            </div>
                            @if($currentPreview)
                                <img src="{{ asset($currentPreview) }}"
                                     alt="{{ $typeLabel($tp) }}"
                                     class="img-fluid rounded border builder-zoomable-preview"
                                     data-role="section-layout-preview-image"
                                     role="button" tabindex="0"
                                     title="{{ __('cms.click_image_to_zoom') }}">
                                <div class="text-muted small mt-2"
                                     data-role="section-layout-preview-empty"
                                     style="display:none;">
                                    {{ __('cms.no_layout_preview_available') }}
                                </div>
                            @else
                                <img src="" alt=""
                                     class="img-fluid rounded border d-none builder-zoomable-preview"
                                     data-role="section-layout-preview-image"
                                     role="button" tabindex="0"
                                     title="{{ __('cms.click_image_to_zoom') }}">
                                <div class="text-muted small mt-2"
                                     data-role="section-layout-preview-empty">
                                    {{ __('cms.no_layout_preview_available') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-muted small mb-1">{{ __('cms.section_translations') }}</p>
            @php $sectionTabPrefix = 'section-'.$sidx; @endphp
            @if($languages->isEmpty())
                <div class="alert alert-warning small mb-0">
                    {{ __('cms.no_languages_configured') }}
                </div>
            @else
                <div data-role="section-lang-tabs" class="section-lang-tabs cms-lang-tabs">
                    <ul class="nav nav-tabs mb-2" role="tablist">
                        @foreach($languages as $index => $lang)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                        id="{{ $sectionTabPrefix }}-tab-{{ $lang->code }}"
                                        data-bs-toggle="tab"
                                        data-bs-target="#{{ $sectionTabPrefix }}-pane-{{ $lang->code }}"
                                        type="button" role="tab">
                                    {{ $lang->flag ?? '' }}
                                    {{ $lang->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach($languages as $index => $lang)
                            @php
                                $tr = $section ? $section->translations->where('locale',
                                $lang->code)->first() :
                                null;
                            @endphp
                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                 id="{{ $sectionTabPrefix }}-pane-{{ $lang->code }}"
                                 role="tabpanel"
                                 aria-labelledby="{{ $sectionTabPrefix }}-tab-{{ $lang->code }}">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-4">
                                        <label
                                            class="form-label small mb-0">{{ __('cms.title') }}
                                            ({{ $lang->code }})</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="sections[{{ $sidx }}][translations][{{ $lang->code }}][title]"
                                               value="{{ old('sections.'.$sidx.'.translations.'.$lang->code.'.title', $tr->title ?? '') }}"
                                               dir="{{ $lang->direction }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="form-label small mb-0">{{ __('cms.subtitle') }}</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               name="sections[{{ $sidx }}][translations][{{ $lang->code }}][subtitle]"
                                               value="{{ old('sections.'.$sidx.'.translations.'.$lang->code.'.subtitle', $tr->subtitle ?? '') }}"
                                               dir="{{ $lang->direction }}">
                                    </div>
                                    <div class="col-12">
                                        <label
                                            class="form-label small mb-0">{{ __('cms.description') }}</label>
                                        <!-- <textarea class="form-control form-control-sm" rows="2"
									name="sections[{{ $sidx }}][translations][{{ $lang->code }}][description]"
									dir="{{ $lang->direction }}">{{ old('sections.'.$sidx.'.translations.'.$lang->code.'.description', $tr->description ?? '') }}</textarea> -->

                                        @include('components.rich-text-editor',
                                        [
                                        'inputId' =>
                                        'section-desc-'.$sidx.'-'.$lang->code,
                                        'inputName' =>
                                        'sections['.$sidx.'][translations]['.$lang->code.'][description]',
                                        'label' =>
                                        __('cms.description') .
                                        ' (' . $lang->name . ')',
                                        'value' =>
                                        old("sections.{$sidx}.translations.{$lang->code}.description",
                                        $tr?->description ?? ''),
                                        'direction' =>
                                        $lang->direction,
                                        'placeholder' =>
                                        __('cms.enter_description'),
                                        'tabPaneId' =>
                                        $sectionTabPrefix.'-pane-'.$lang->code,
                                        ])
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @php
                $sectionExistingImages = ($section ?? null)
                    ? $section->getMedia('gallery')
                    : collect();
            @endphp
            @include('components.gallery-upload', [
            'deferGalleryInit' => true,
            'inputId' => 'section-gallery-'.$sidx,
            'inputName' => 'sections['.$sidx.'][gallery]',
            'collection' => 'gallery',
            'label' => __('cms.gallery_images'),
            'existingImages' => $sectionExistingImages,
            ])
            <hr>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold">{{ __('cms.section_links') }}</span>
                <button type="button" class="btn btn-sm btn-outline-primary"
                        data-role="add-section-link">{{ __('cms.add_link') }}</button>
            </div>
            <div class="section-links-wrap mb-3" data-role="section-links">
                @if($section && $section->relationLoaded('links') &&
                $section->links->isNotEmpty())
                    @foreach($section->links->sortBy('order') as $lnk)
                        @include('cms.pages.partials.builder-link-row', [
                        'languages' => $languages,
                        'namePrefix' => 'sections['.$sidx.'][links]['.$loop->index.']',
                        'link' => $lnk,
                        ])
                    @endforeach
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold">{{ __('cms.items') }}</span>
                <button type="button" class="btn btn-sm btn-primary" data-role="add-item">{{ __('cms.add_item') }}</button>
            </div>
            <div class="items-wrap" data-role="items-wrap">
                @if($section && $section->relationLoaded('items') &&
                $section->items->isNotEmpty())
                    @foreach($section->items->sortBy('order') as $it)
                        @include('cms.pages.partials.builder-item', [
                        'languages' => $languages,
                        'sidx' => $sidx,
                        'iidx' => $loop->index,
                        'item' => $it,
                        ])
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
