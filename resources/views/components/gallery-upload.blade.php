@php
    use App\Support\Cms\CmsGalleryMedia;

    $deferGalleryInit = $deferGalleryInit ?? false;
    $compact = $compact ?? false;
    $inputId = $inputId ?? 'gallery_upload_' . uniqid();
    $inputName = $inputName ?? 'gallery';
    $collection = $collection ?? 'gallery';
    $existingImages = $existingImages ?? collect();
    // Normalize so we don't end up with "[][]" when callers already pass an array name (e.g. "...[gallery][]").
    $galleryNameRoot = preg_replace('/\[\]$/', '', $inputName);
    $fileInputName = $galleryNameRoot . '[]';
    if (preg_match('/^(.*)\[gallery\]$/', $galleryNameRoot, $galleryNameMatch)) {
        $existingAltBase = $galleryNameMatch[1] . '[gallery_existing_alt]';
        $replaceBase = $galleryNameMatch[1] . '[gallery_replace]';
        $newAltName = $galleryNameMatch[1] . '[gallery_new_alt][]';
    } else {
        $existingAltBase = 'gallery_existing_alt';
        $replaceBase = 'gallery_replace';
        $newAltName = 'gallery_new_alt[]';
    }
    $acceptTypes = implode(',', array_merge(
        array_map(fn ($ext) => '.'.$ext, CmsGalleryMedia::IMAGE_EXTENSIONS),
        array_map(fn ($ext) => '.'.$ext, CmsGalleryMedia::VIDEO_EXTENSIONS)
    ));
@endphp
<div class="mb-3 gallery-upload-field{{ $compact ? ' gallery-upload-field-compact' : '' }}">
    <label class="form-label{{ $compact ? ' small mb-0' : '' }}" for="{{ $inputId }}">{{ $label ?? __('Gallery') }}</label>
    <div class="gallery-upload-container"
         data-collection="{{ $collection }}"
         data-new-alt-name="{{ $newAltName }}"
         data-replace-base="{{ $replaceBase }}">
        <input type="file"
               id="{{ $inputId }}"
               name="{{ $fileInputName }}"
               class="form-control{{ $compact ? ' form-control-sm' : '' }} gallery-input"
               accept="{{ $acceptTypes }}"
               multiple>

        <small class="text-muted d-block mt-1">{{ __('cms.gallery_media_hint') }}</small>
        <small class="text-muted d-block">{{ __('cms.gallery_replace_hint') }}</small>

        <div class="gallery-preview mt-3" id="gallery-preview-{{ $inputId }}">
            @if(isset($existingImages) && $existingImages->count() > 0)
                @foreach($existingImages as $image)
                    <div class="gallery-item" data-media-id="{{ $image->id }}">
                        <div class="gallery-item-media">
                            @if(CmsGalleryMedia::isVideo($image))
                                <video src="{{ CmsGalleryMedia::previewUrl($image) }}" class="img-thumbnail gallery-video-preview" muted playsinline></video>
                                <span class="gallery-media-badge">{{ __('cms.video') }}</span>
                            @else
                                <img src="{{ CmsGalleryMedia::previewUrl($image) }}" alt="{{ CmsGalleryMedia::alt($image) }}" class="img-thumbnail">
                            @endif
                            <div class="gallery-item-actions">
                                <button type="button"
                                        class="btn btn-sm btn-primary gallery-replace-existing"
                                        title="{{ __('cms.replace_media_file') }}"
                                        data-media-id="{{ $image->id }}">
                                    <i class="mdi mdi-swap-horizontal"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm btn-danger gallery-remove-existing"
                                        data-media-id="{{ $image->id }}"
                                        data-collection="{{ $collection }}">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                            <input type="file"
                                   class="d-none gallery-replace-input"
                                   name="{{ $replaceBase }}[{{ $image->id }}]"
                                   accept="{{ $acceptTypes }}">
                        </div>
                        <input type="text"
                               class="form-control form-control-sm mt-1 gallery-alt-input"
                               data-media-id="{{ $image->id }}"
                               name="{{ $existingAltBase }}[{{ $image->id }}]"
                               value="{{ CmsGalleryMedia::alt($image) }}"
                               maxlength="255"
                               placeholder="{{ __('cms.alt_text') }}">
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@once
    @push('styles')
        <style>
            .gallery-preview {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            .gallery-item {
                position: relative;
                display: inline-block;
                width: 150px;
                vertical-align: top;
            }
            .gallery-item-media {
                position: relative;
            }
            .gallery-item img,
            .gallery-item video.gallery-video-preview {
                width: 150px;
                height: 150px;
                object-fit: cover;
                display: block;
            }
            .gallery-item-actions {
                position: absolute;
                top: 5px;
                right: 5px;
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
            .gallery-item-actions .btn {
                width: 28px;
                height: 28px;
                padding: 0;
                line-height: 1;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .gallery-media-badge {
                position: absolute;
                left: 5px;
                bottom: 5px;
                background: rgba(0, 0, 0, 0.65);
                color: #fff;
                font-size: 11px;
                padding: 2px 6px;
                border-radius: 4px;
            }
            .gallery-item.is-replaced .gallery-item-media::after {
                content: '';
                position: absolute;
                inset: 0;
                border: 2px solid #0d6efd;
                pointer-events: none;
            }
        </style>
    @endpush
@endonce

@once
    @push('scripts')
        <script>
            window.CmsGalleryUpload = window.CmsGalleryUpload || {
                isImage: function(file) {
                    return file && file.type && file.type.startsWith('image/');
                },
                isVideo: function(file) {
                    return file && file.type && file.type.startsWith('video/');
                },
                updateExistingPreview: function(galleryItem, file) {
                    if (!galleryItem || !file) {
                        return;
                    }
                    var mediaWrap = galleryItem.querySelector('.gallery-item-media');
                    if (!mediaWrap) {
                        return;
                    }
                    var actions = mediaWrap.querySelector('.gallery-item-actions');
                    var replaceInput = mediaWrap.querySelector('.gallery-replace-input');
                    var oldMedia = mediaWrap.querySelector('img, video');
                    var oldBadge = mediaWrap.querySelector('.gallery-media-badge');
                    if (oldMedia) {
                        oldMedia.remove();
                    }
                    if (oldBadge) {
                        oldBadge.remove();
                    }

                    if (this.isVideo(file)) {
                        var video = document.createElement('video');
                        video.src = URL.createObjectURL(file);
                        video.className = 'img-thumbnail gallery-video-preview';
                        video.muted = true;
                        video.playsInline = true;
                        mediaWrap.insertBefore(video, actions || replaceInput || null);
                        var badge = document.createElement('span');
                        badge.className = 'gallery-media-badge';
                        badge.textContent = @json(__('cms.video'));
                        mediaWrap.insertBefore(badge, actions || replaceInput || null);
                    } else {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'Preview';
                            img.className = 'img-thumbnail';
                            mediaWrap.insertBefore(img, actions || replaceInput || null);
                        };
                        reader.readAsDataURL(file);
                    }
                    galleryItem.classList.add('is-replaced');
                }
            };
        </script>
    @endpush
@endonce

@unless($deferGalleryInit)
    @push('scripts')
        <script>
            (function() {
                var inputId = '{{ $inputId }}';
                var input = document.getElementById(inputId);
                var previewContainer = document.getElementById('gallery-preview-' + inputId);
                var collection = '{{ $collection }}';
                var newAltName = '{{ $newAltName }}';
                var selectedFiles = [];

                function altFieldHtml() {
                    return '<input type="text" class="form-control form-control-sm mt-1 gallery-alt-input" name="' +
                        newAltName + '" maxlength="255" placeholder="{{ __('cms.alt_text') }}">';
                }

                function appendGalleryPreview(file) {
                    var galleryItem = document.createElement('div');
                    galleryItem.className = 'gallery-item';
                    galleryItem.setAttribute('data-file-name', file.name);

                    if (window.CmsGalleryUpload.isVideo(file)) {
                        var videoUrl = URL.createObjectURL(file);
                        galleryItem.innerHTML =
                            '<div class="gallery-item-media">' +
                            '<video src="' + videoUrl + '" class="img-thumbnail gallery-video-preview" muted playsinline></video>' +
                            '<span class="gallery-media-badge">{{ __("cms.video") }}</span>' +
                            '<button type="button" class="btn btn-sm btn-danger gallery-remove-new"><i class="mdi mdi-delete"></i></button>' +
                            '</div>' + altFieldHtml();
                        previewContainer.appendChild(galleryItem);
                        bindRemoveNew(galleryItem, file);
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        galleryItem.innerHTML =
                            '<div class="gallery-item-media">' +
                            '<img src="' + e.target.result + '" alt="Preview" class="img-thumbnail">' +
                            '<button type="button" class="btn btn-sm btn-danger gallery-remove-new"><i class="mdi mdi-delete"></i></button>' +
                            '</div>' + altFieldHtml();
                        previewContainer.appendChild(galleryItem);
                        bindRemoveNew(galleryItem, file);
                    };
                    reader.readAsDataURL(file);
                }

                function bindRemoveNew(galleryItem, file) {
                    galleryItem.querySelector('.gallery-remove-new').addEventListener('click', function() {
                        selectedFiles = selectedFiles.filter(function(f) {
                            return f.name !== file.name;
                        });

                        var dt = new DataTransfer();
                        selectedFiles.forEach(function(f) {
                            dt.items.add(f);
                        });
                        input.files = dt.files;
                        galleryItem.remove();
                    });
                }

                function initGallery() {
                    if (!input || !previewContainer) {
                        return;
                    }

                    input.addEventListener('change', function() {
                        Array.from(input.files).forEach(function(file) {
                            if (!window.CmsGalleryUpload.isImage(file) && !window.CmsGalleryUpload.isVideo(file)) {
                                return;
                            }
                            if (selectedFiles.some(function(f) { return f.name === file.name; })) {
                                return;
                            }
                            selectedFiles.push(file);
                            appendGalleryPreview(file);
                        });
                    });

                    previewContainer.addEventListener('click', function(e) {
                        var replaceBtn = e.target.closest('.gallery-replace-existing');
                        if (!replaceBtn) {
                            return;
                        }
                        e.preventDefault();
                        var item = replaceBtn.closest('.gallery-item');
                        var replaceInput = item && item.querySelector('.gallery-replace-input');
                        if (replaceInput) {
                            replaceInput.click();
                        }
                    });

                    previewContainer.addEventListener('change', function(e) {
                        var replaceInput = e.target.closest('.gallery-replace-input');
                        if (!replaceInput || !replaceInput.files || !replaceInput.files[0]) {
                            return;
                        }
                        var file = replaceInput.files[0];
                        if (!window.CmsGalleryUpload.isImage(file) && !window.CmsGalleryUpload.isVideo(file)) {
                            replaceInput.value = '';
                            return;
                        }
                        window.CmsGalleryUpload.updateExistingPreview(replaceInput.closest('.gallery-item'), file);
                    });
                }

                function initExistingImageRemoval() {
                    var removeButtons = document.querySelectorAll('.gallery-remove-existing[data-collection="' + collection + '"]');
                    removeButtons.forEach(function(button) {
                        button.addEventListener('click', function() {
                            var mediaId = this.getAttribute('data-media-id');
                            var galleryItem = this.closest('.gallery-item');

                            Swal.fire({
                                title: '{{ __("Are you sure?") }}',
                                text: '{{ __("You won\'t be able to revert this!") }}',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '{{ __("Yes, delete it!") }}'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch('{{ route("cms.media.index") }}/' + mediaId, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        }
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                galleryItem.remove();
                                                Swal.fire('{{ __("Deleted!") }}', data.message, 'success');
                                            } else {
                                                Swal.fire('{{ __("Error!") }}', data.message || '{{ __("An error occurred") }}', 'error');
                                            }
                                        })
                                        .catch(error => {
                                            Swal.fire('{{ __("Error!") }}', '{{ __("An error occurred") }}', 'error');
                                        });
                                }
                            });
                        });
                    });
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        initGallery();
                        initExistingImageRemoval();
                    });
                } else {
                    initGallery();
                    initExistingImageRemoval();
                }
            })();
        </script>
    @endpush
@endunless
