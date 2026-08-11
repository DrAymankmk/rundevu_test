@php
    use App\Support\Cms\CmsGalleryMedia;

    $deferGalleryInit = $deferGalleryInit ?? false;
    $acceptTypes = implode(',', array_merge(
        array_map(fn ($ext) => '.'.$ext, CmsGalleryMedia::IMAGE_EXTENSIONS),
        array_map(fn ($ext) => '.'.$ext, CmsGalleryMedia::VIDEO_EXTENSIONS)
    ));
@endphp
<div class="mb-3">
    <label class="form-label">{{ $label ?? __('Gallery') }}</label>
    <div class="gallery-upload-container" data-collection="{{ $collection }}">
        <input type="file"
               id="{{ $inputId }}"
               name="{{ $inputName }}[]"
               class="form-control gallery-input"
               accept="{{ $acceptTypes }}"
               multiple>

        <small class="text-muted d-block mt-1">{{ __('cms.gallery_media_hint') }}</small>

        <div class="gallery-preview mt-3" id="gallery-preview-{{ $inputId }}">
            @if(isset($existingImages) && $existingImages->count() > 0)
                @foreach($existingImages as $image)
                    <div class="gallery-item" data-media-id="{{ $image->id }}">
                        @if(CmsGalleryMedia::isVideo($image))
                            <video src="{{ CmsGalleryMedia::previewUrl($image) }}" class="img-thumbnail gallery-video-preview" muted playsinline></video>
                            <span class="gallery-media-badge">{{ __('cms.video') }}</span>
                        @else
                            <img src="{{ CmsGalleryMedia::previewUrl($image) }}" alt="{{ $image->name }}" class="img-thumbnail">
                        @endif
                        <button type="button" class="btn btn-sm btn-danger gallery-remove-existing" data-media-id="{{ $image->id }}" data-collection="{{ $collection }}">
                            <i class="mdi mdi-delete"></i>
                        </button>
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
            }
            .gallery-item img,
            .gallery-item video.gallery-video-preview {
                width: 150px;
                height: 150px;
                object-fit: cover;
                display: block;
            }
            .gallery-item .btn {
                position: absolute;
                top: 5px;
                right: 5px;
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
        </style>
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
                var selectedFiles = [];

                function isGalleryImage(file) {
                    return file.type && file.type.startsWith('image/');
                }

                function isGalleryVideo(file) {
                    return file.type && file.type.startsWith('video/');
                }

                function appendGalleryPreview(file) {
                    var galleryItem = document.createElement('div');
                    galleryItem.className = 'gallery-item';
                    galleryItem.setAttribute('data-file-name', file.name);

                    if (isGalleryVideo(file)) {
                        var videoUrl = URL.createObjectURL(file);
                        galleryItem.innerHTML =
                            '<video src="' + videoUrl + '" class="img-thumbnail gallery-video-preview" muted playsinline></video>' +
                            '<span class="gallery-media-badge">{{ __("cms.video") }}</span>' +
                            '<button type="button" class="btn btn-sm btn-danger gallery-remove-new"><i class="mdi mdi-delete"></i></button>';
                        previewContainer.appendChild(galleryItem);
                        bindRemoveNew(galleryItem, file);
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        galleryItem.innerHTML =
                            '<img src="' + e.target.result + '" alt="Preview" class="img-thumbnail">' +
                            '<button type="button" class="btn btn-sm btn-danger gallery-remove-new"><i class="mdi mdi-delete"></i></button>';
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
                            if (!isGalleryImage(file) && !isGalleryVideo(file)) {
                                return;
                            }
                            if (selectedFiles.some(function(f) { return f.name === file.name; })) {
                                return;
                            }
                            selectedFiles.push(file);
                            appendGalleryPreview(file);
                        });
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
