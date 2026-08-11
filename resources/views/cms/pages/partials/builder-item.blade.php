@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\CmsLanguage> $languages */
        $item = $item ?? null;
@endphp
<div class="card mb-2 item-card border-secondary" data-role="item-card">
    <div class="card-header py-2 d-flex justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
            <button type="button"
                    class="btn btn-sm btn-light border px-1 py-0 flex-shrink-0"
                    data-bs-toggle="collapse"
                    data-bs-target="#item-collapse-{{ $sidx }}-{{ $iidx }}"
                    aria-expanded="true"
                    aria-controls="item-collapse-{{ $sidx }}-{{ $iidx }}"
                    data-role="item-collapse-toggle" title="{{ __('cms.toggle_item') }}">
                <i class="mdi mdi-chevron-down"></i>
            </button>
            <strong class="small text-truncate">{{ __('cms.item') }}</strong>
            @if($item)
                <span class="small text-muted text-truncate">— {{ $item->slug }}</span>
            @endif
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0"
                data-role="remove-item">&times;</button>
    </div>
    <div id="item-collapse-{{ $sidx }}-{{ $iidx }}" class="collapse show" data-role="item-collapse">
        <div class="card-body py-2">
            @if($item)
                <input type="hidden" name="sections[{{ $sidx }}][items][{{ $iidx }}][id]"
                       value="{{ $item->id }}">
            @endif
            <input type="hidden" name="sections[{{ $sidx }}][items][{{ $iidx }}][is_active]"
                   value="0">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small mb-0">{{ __('cms.slug') }}
                        <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm"
                           name="sections[{{ $sidx }}][items][{{ $iidx }}][slug]"
                           value="{{ old('sections.'.$sidx.'.items.'.$iidx.'.slug', $item?->slug ?? '') }}"
                           placeholder="{{ __('cms.slug') }}">
                </div>
                <div class="col-md-2">
                    <label
                        class="form-label small mb-0">{{ __('cms.type') }}</label>
                    <input type="text" class="form-control form-control-sm"
                           name="sections[{{ $sidx }}][items][{{ $iidx }}][type]"
                           value="{{ old('sections.'.$sidx.'.items.'.$iidx.'.type', $item?->type ?? 'default') }}">
                </div>
                <div class="col-md-1">
                    <label
                        class="form-label small mb-0">{{ __('cms.order') }}</label>
                    <input type="number" class="form-control form-control-sm"
                           name="sections[{{ $sidx }}][items][{{ $iidx }}][order]"
                           value="{{ old('sections.'.$sidx.'.items.'.$iidx.'.order', $item?->order ?? 0) }}">
                </div>
                <div class="col-md-2">
                    <div
                        class="d-flex align-items-center justify-content-between gap-2 mt-3">
                        @php $activeId = 'item-active-'.$sidx.'-'.$iidx;
                        @endphp
                        <label class="form-label small mb-0"
                               for="{{ $activeId }}">{{ __('cms.active') }}</label>
                        <div class="form-check form-switch m-0">
                            <input type="checkbox"
                                   class="form-check-input"
                                   role="switch"
                                   id="{{ $activeId }}"
                                   name="sections[{{ $sidx }}][items][{{ $iidx }}][is_active]"
                                   value="1"
                                {{ old('sections.'.$sidx.'.items.'.$iidx.'.is_active', $item?->is_active ?? true) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-2">
            <div class="row g-3 align-items-start">
                <div class="col-xl-12">
                    <p class="small text-muted mb-1">
                        {{ __('cms.translations') }}</p>
                    @php $itemTabPrefix = 'item-'.$sidx.'-'.$iidx; @endphp
                    @if($languages->isEmpty())
                        <div class="alert alert-warning small mb-0">
                            {{ __('cms.no_languages_configured') }}
                        </div>
                    @else
                        <div data-role="item-lang-tabs"
                             class="item-lang-tabs cms-lang-tabs">
                            <ul class="nav nav-tabs mb-2" role="tablist">
                                @foreach($languages as $index => $lang)
                                    <li class="nav-item"
                                        role="presentation">
                                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                                id="{{ $itemTabPrefix }}-tab-{{ $lang->code }}"
                                                data-bs-toggle="tab"
                                                data-bs-target="#{{ $itemTabPrefix }}-pane-{{ $lang->code }}"
                                                type="button"
                                                role="tab">
                                            {{ $lang->flag ?? '' }}
                                            {{ $lang->name }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($languages as $index => $lang)
                                    @php
                                        $tr = $item ?
                                        $item->translations->where('locale',
                                        $lang->code)->first() : null;
                                    @endphp
                                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                         id="{{ $itemTabPrefix }}-pane-{{ $lang->code }}"
                                         role="tabpanel"
                                         aria-labelledby="{{ $itemTabPrefix }}-tab-{{ $lang->code }}">
                                        <div class="row g-2 mb-2">
                                            <div
                                                class="col-md-3">
                                                <label
                                                    class="form-label small mb-0">{{ __('cms.title') }}
                                                    ({{ $lang->code }})
                                                    *</label>
                                                <input type="text"
                                                       class="form-control form-control-sm"
                                                       name="sections[{{ $sidx }}][items][{{ $iidx }}][translations][{{ $lang->code }}][title]"
                                                       value="{{ old('sections.'.$sidx.'.items.'.$iidx.'.translations.'.$lang->code.'.title', $tr->title ?? '') }}"
                                                       dir="{{ $lang->direction }}">
                                            </div>
                                            <div
                                                class="col-md-3">
                                                <label
                                                    class="form-label small mb-0">{{ __('cms.subtitle') }}</label>
                                                <input type="text"
                                                       class="form-control form-control-sm"
                                                       name="sections[{{ $sidx }}][items][{{ $iidx }}][translations][{{ $lang->code }}][sub_title]"
                                                       value="{{ old('sections.'.$sidx.'.items.'.$iidx.'.translations.'.$lang->code.'.sub_title', $tr->sub_title ?? '') }}"
                                                       dir="{{ $lang->direction }}">
                                            </div>
                                            <div
                                                class="col-md-6">
                                                <label
                                                    class="form-label small mb-0">{{ __('cms.icon_class') }}</label>
                                                @php
                                                    $iconInputId
                                                    =
                                                    'item-icon-'.$sidx.'-'.$iidx.'-'.$lang->code;
                                                @endphp
                                                @include('components.icon-picker',
                                                [
                                                'inputId'
                                                =>
                                                $iconInputId,
                                                'inputName'
                                                =>
                                                'sections['.$sidx.'][items]['.$iidx.'][translations]['.$lang->code.'][icon]',
                                                'value' =>
                                                old('sections.'.$sidx.'.items.'.$iidx.'.translations.'.$lang->code.'.icon',
                                                $tr->icon
                                                ?? ''),
                                                'inputClass'
                                                =>
                                                'form-control
                                                form-control-sm
                                                icon-picker-input',
                                                'compact'
                                                => true,
                                                'sharedModalId'
                                                =>
                                                'cmsBuilderIconPicker',
                                                ])
                                            </div>
                                            <div class="col-12">
                                                <label
                                                    class="form-label small mb-0">{{ __('cms.content') }}</label>
                                                <textarea class="form-control form-control-sm"
                                                          rows="2"
                                                          name="sections[{{ $sidx }}][items][{{ $iidx }}][translations][{{ $lang->code }}][content]"
                                                          dir="{{ $lang->direction }}">{{ old('sections.'.$sidx.'.items.'.$iidx.'.translations.'.$lang->code.'.content', $tr->content ?? '') }}</textarea>
                                            </div>
                                            <div class="col-12">
                                                <hr
                                                    class="my-2">
                                                <h6
                                                    class="small fw-semibold mb-2">
                                                    {{ __('cms.images') }}
                                                    ({{ $lang->name }})
                                                </h6>
                                                @php
                                                    $mainImgUrl
                                                    = ($item
                                                    ?? null)
                                                    ?
                                                    ($item->getFirstAccessibleMediaUrl('images_'.$lang->code)
                                                    ?: null) :
                                                    null;
                                                    $iconImgUrl
                                                    = ($item
                                                    ?? null)
                                                    ?
                                                    ($item->getFirstAccessibleMediaUrl('icons_'.$lang->code)
                                                    ?: null) :
                                                    null;
                                                @endphp
                                                <div
                                                    class="row g-3">
                                                    <div
                                                        class="col-md-6">
                                                        @include('components.image-upload',
                                                        [
                                                        'inputId'
                                                        =>
                                                        'item-image-s'.$sidx.'-i'.$iidx.'-'.$lang->code,
                                                        'inputName'
                                                        =>
                                                        'sections['.$sidx.'][items]['.$iidx.'][translations]['.$lang->code.'][image]',
                                                        'collection'
                                                        =>
                                                        'images_'.$lang->code,
                                                        'label'
                                                        =>
                                                        __('cms.main_image'),
                                                        'existingImage'
                                                        =>
                                                        $mainImgUrl,
                                                        ])
                                                    </div>
                                                    <div
                                                        class="col-md-6">
                                                        @include('components.image-upload',
                                                        [
                                                        'inputId'
                                                        =>
                                                        'item-iconimg-s'.$sidx.'-i'.$iidx.'-'.$lang->code,
                                                        'inputName'
                                                        =>
                                                        'sections['.$sidx.'][items]['.$iidx.'][translations]['.$lang->code.'][icon_image]',
                                                        'collection'
                                                        =>
                                                        'icons_'.$lang->code,
                                                        'label'
                                                        =>
                                                        __('cms.icon_image'),
                                                        'existingImage'
                                                        =>
                                                        $iconImgUrl,
                                                        ])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <!-- <div class="col-xl-4">
						<div class="mt-4 mt-xl-0">
							@include('components.gallery-upload', [
							'deferGalleryInit' => true,
							'inputId' => 'item-gallery-s'.$sidx.'-i'.$iidx,
							'inputName' => 'sections['.$sidx.'][items]['.$iidx.'][gallery]',
							'collection' => 'gallery',
							'label' => __('cms.gallery_images'),
							'existingImages' => ($item ?? null) ? $item->getMedia('gallery') :
							collect([]),
							])
                </div>
            </div> -->
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="small fw-semibold">{{ __('cms.item_links') }}</span>
                <button type="button" class="btn btn-sm btn-outline-primary"
                        data-role="add-item-link">{{ __('cms.add_link') }}</button>
            </div>
            <div class="item-links-wrap" data-role="item-links">
                @if($item && $item->relationLoaded('links') &&
                $item->links->isNotEmpty())
                    @foreach($item->links->sortBy('order') as $lnk)
                        @include('cms.pages.partials.builder-link-row', [
                        'languages' => $languages,
                        'namePrefix' =>
                        'sections['.$sidx.'][items]['.$iidx.'][links]['.$loop->index.']',
                        'link' => $lnk,
                        ])
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
