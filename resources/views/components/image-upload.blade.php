@php
    $inputId = $inputId ?? 'image_upload_' . uniqid();
    $inputName = $inputName ?? 'image';
    $collection = $collection ?? 'images';
    $label = $label ?? __('Image');
    $existingImage = $existingImage ?? null;
    $previewId = 'preview_' . $inputId;
@endphp

<div class="mb-3">
    <label class="form-label">{{ $label }}</label>
    <div class="image-upload-wrapper">
        <!-- Preview Container -->
        <div class="image-preview-container mb-2" id="{{ $previewId }}" style="{{ $existingImage ? '' : 'display: none;' }}">
            <div class="position-relative d-inline-block">
                <img src="{{ $existingImage }}" 
                     alt="Preview" 
                     class="img-thumbnail" 
                     style="max-width: 200px; max-height: 200px; object-fit: cover;"
                     id="{{ $previewId }}_img">
                <button type="button" 
                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                        onclick="removeImagePreview('{{ $previewId }}', '{{ $inputId }}')"
                        style="border-radius: 50%; width: 24px; height: 24px; padding: 0; line-height: 1;">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
        </div>

        <!-- File Input -->
        <input type="file" 
               class="form-control @error($inputName) is-invalid @enderror" 
               id="{{ $inputId }}" 
               name="{{ $inputName }}" 
               accept="image/jpeg,image/png,image/gif,image/webp"
               onchange="previewImage(this, '{{ $previewId }}')">
        
        @error($inputName)
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        
        <small class="text-muted">{{ __('Accepted formats: JPEG, PNG, GIF, WebP') }}</small>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input, previewId) {
    const previewContainer = document.getElementById(previewId);
    const previewImg = document.getElementById(previewId + '_img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
    }
}

function removeImagePreview(previewId, inputId) {
    const previewContainer = document.getElementById(previewId);
    const input = document.getElementById(inputId);
    
    previewContainer.style.display = 'none';
    if (input) {
        input.value = '';
    }
}
</script>
@endpush
