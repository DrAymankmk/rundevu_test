@include('components.rich-text-editor-assets')
@php
    $quillOptions = [
        'placeholder' => $placeholder ?? __('Enter content...'),
        'rtl' => isset($direction) && $direction === 'rtl',
    ];
@endphp
<div class="mb-3 cms-quill-root"
	data-quill-editor-id="editor-{{ $inputId }}"
	data-quill-textarea-id="{{ $inputId }}"
	@if(!empty($tabPaneId ?? null)) data-quill-tab-pane="{{ $tabPaneId }}" @endif
	data-quill-options="{{ e(json_encode($quillOptions)) }}">
	<label class="form-label">{{ $label ?? __('Content') }}</label>
	<div id="editor-{{ $inputId }}" style="min-height: 200px;"></div>
	<textarea name="{{ $inputName }}" id="{{ $inputId }}" style="display: none;">{{ $value ?? '' }}</textarea>
</div>
