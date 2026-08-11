{{-- Icon Picker Component --}}
{{-- Usage: @include('components.icon-picker', ['inputId' => 'icon_field', 'inputName' => 'icon']) --}}
{{-- Builder: pass sharedModalId => 'cmsBuilderIconPicker' and include shared modal once on the page --}}

@php
    $pickerInputId = $inputId ?? 'icon';
    $pickerInputName = $inputName ?? 'icon';
    $pickerValue = $value ?? '';
    $pickerSharedModalId = $sharedModalId ?? null;
    $pickerRenderSharedModal = !empty($renderSharedModal);
@endphp

@if($pickerRenderSharedModal)
    <div class="modal fade icon-picker-modal cms-icon-picker-shared-modal" id="iconPickerModal-{{ $sharedModalId }}"
         tabindex="-1" aria-hidden="true" data-role="icon-picker-shared-modal">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Select Icon') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <input type="text" class="form-control icon-search" data-role="icon-picker-search"
                                   placeholder="{{ __('Search icons...') }}">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select icon-library-filter" data-role="icon-picker-library-filter">
                                <option value="all">{{ __('All Icons') }}</option>
                                <option value="mdi">Material Design Icons</option>
                                <option value="fa">Font Awesome</option>
                            </select>
                        </div>
                    </div>
                    <div class="icon-grid" data-role="icon-picker-grid"></div>
                    <div class="text-center py-4 icon-loading" data-role="icon-picker-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="selected-icon-preview me-auto">
                        <span class="text-muted">{{ __('Selected') }}:</span>
                        <span class="selected-icon-display" data-role="icon-picker-selected-display">
						<i class="mdi mdi-emoticon-outline"></i>
						<code class="ms-2">{{ __('None') }}</code>
					</span>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" data-role="icon-picker-confirm"
                            data-bs-dismiss="modal">{{ __('Select') }}</button>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="icon-picker-wrapper">
        <div class="input-group{{ !empty($compact) ? ' input-group-sm' : '' }}">
		<span class="input-group-text icon-preview" id="preview-{{ $pickerInputId }}">
			@if($pickerValue)
                <i class="{{ $pickerValue }}"></i>
            @else
                <i class="mdi mdi-emoticon-outline"></i>
            @endif
		</span>
            <input type="text"
                   class="{{ $inputClass ?? 'form-control icon-picker-input' }}"
                   id="{{ $pickerInputId }}"
                   name="{{ $pickerInputName }}"
                   value="{{ $pickerValue }}"
                   placeholder="{{ __('Click to select icon') }}"
                   readonly
                   @if($pickerSharedModalId)
                       data-role="icon-picker-trigger"
                   data-shared-modal="{{ $pickerSharedModalId }}"
                   @else
                       data-bs-toggle="modal"
                   data-bs-target="#iconPickerModal-{{ $pickerInputId }}"
                @endif>
            <button type="button" class="btn btn-outline-secondary clear-icon-btn" data-input="{{ $pickerInputId }}">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    </div>

    @if(!$pickerSharedModalId)
        <div class="modal fade icon-picker-modal" id="iconPickerModal-{{ $pickerInputId }}" tabindex="-1" aria-hidden="true"
             data-icon-picker-input="{{ $pickerInputId }}">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Select Icon') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <input type="text" class="form-control icon-search" placeholder="{{ __('Search icons...') }}">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select icon-library-filter">
                                    <option value="all">{{ __('All Icons') }}</option>
                                    <option value="mdi">Material Design Icons</option>
                                    <option value="fa">Font Awesome</option>
                                </select>
                            </div>
                        </div>
                        <div class="icon-grid" id="iconGrid-{{ $pickerInputId }}"></div>
                        <div class="text-center py-4 icon-loading" id="iconLoading-{{ $pickerInputId }}">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="selected-icon-preview me-auto">
                            <span class="text-muted">{{ __('Selected') }}:</span>
                            <span class="selected-icon-display" id="selectedIconDisplay-{{ $pickerInputId }}">
						<i class="mdi mdi-emoticon-outline"></i>
						<code class="ms-2">{{ __('None') }}</code>
					</span>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-primary confirm-icon-btn" data-input="{{ $pickerInputId }}"
                                data-bs-dismiss="modal">{{ __('Select') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

@once
    @push('styles')
        <style>
            .icon-picker-wrapper .icon-preview {
                font-size: 1.25rem;
                min-width: 45px;
                justify-content: center;
            }
            .icon-picker-wrapper .icon-picker-input {
                cursor: pointer;
                background-color: #fff;
            }
            .icon-picker-wrapper .clear-icon-btn {
                border-color: #ced4da;
            }
            .icon-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
                gap: 8px;
                max-height: 400px;
                overflow-y: auto;
                padding: 5px;
            }
            .icon-grid-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 12px 8px;
                border: 2px solid transparent;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s ease;
                background: #f8f9fa;
            }
            .icon-grid-item:hover {
                background: #e9ecef;
                border-color: #6c757d;
            }
            .icon-grid-item.selected {
                background: #e7f1ff;
                border-color: #0d6efd;
            }
            .icon-grid-item i {
                font-size: 1.5rem;
                margin-bottom: 4px;
            }
            .icon-grid-item .icon-name {
                font-size: 0.65rem;
                text-align: center;
                color: #6c757d;
                word-break: break-all;
                line-height: 1.1;
                max-height: 2.2em;
                overflow: hidden;
            }
            .selected-icon-preview {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .selected-icon-display i {
                font-size: 1.5rem;
            }
            .icon-loading {
                display: none;
            }
            .icon-picker-modal {
                z-index: 2005 !important;
            }
            .modal-backdrop.icon-picker-backdrop {
                z-index: 2000 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function() {
                const iconLibrary = {
                    mdi: [
                        'account', 'account-circle', 'account-group', 'account-plus', 'account-search',
                        'alert', 'alert-circle', 'alert-outline', 'archive', 'arrow-left',
                        'arrow-right', 'arrow-up', 'arrow-down', 'bell', 'bell-outline',
                        'bookmark', 'bookmark-outline', 'book', 'book-open', 'briefcase',
                        'calendar', 'calendar-check', 'camera', 'cart', 'cart-outline',
                        'chart-bar', 'chart-line', 'chart-pie', 'check', 'check-circle',
                        'chevron-left', 'chevron-right', 'chevron-up', 'chevron-down', 'clock',
                        'clock-outline', 'close', 'close-circle', 'cloud', 'cloud-download',
                        'cloud-upload', 'code-tags', 'cog', 'cog-outline', 'comment',
                        'comment-outline', 'content-copy', 'content-save', 'credit-card', 'delete',
                        'delete-outline', 'download', 'email', 'email-outline', 'eye',
                        'eye-off', 'facebook', 'file', 'file-document', 'file-image',
                        'file-pdf-box', 'filter', 'flag', 'folder', 'folder-open',
                        'format-bold', 'format-italic', 'format-underline', 'gift', 'github',
                        'google', 'grid', 'heart', 'heart-outline', 'help',
                        'help-circle', 'home', 'home-outline', 'image', 'image-multiple',
                        'information', 'information-outline', 'instagram', 'key', 'keyboard',
                        'language', 'layers', 'lightbulb', 'lightbulb-outline', 'link',
                        'linkedin', 'list-box', 'location-enter', 'lock', 'lock-open',
                        'login', 'logout', 'magnify', 'map', 'map-marker',
                        'menu', 'message', 'message-text', 'microphone', 'minus',
                        'minus-circle', 'monitor', 'music', 'note', 'notebook',
                        'notification-clear-all', 'open-in-new', 'palette', 'paperclip', 'pause',
                        'pencil', 'pencil-outline', 'phone', 'phone-outline', 'pin',
                        'play', 'playlist-check', 'plus', 'plus-circle', 'power',
                        'printer', 'puzzle', 'qrcode', 'refresh', 'reply',
                        'rocket', 'rocket-launch', 'rss', 'share', 'share-variant',
                        'shield', 'shield-check', 'shopping', 'shuffle', 'skip-next',
                        'skip-previous', 'sort', 'speaker', 'square-edit-outline', 'star',
                        'star-outline', 'stop', 'store', 'sync', 'table',
                        'tablet', 'tag', 'tag-outline', 'target', 'text',
                        'thumb-up', 'thumb-down', 'timer', 'toggle-switch', 'tools',
                        'translate', 'trash-can', 'trending-up', 'trophy', 'twitter',
                        'undo', 'upload', 'video', 'view-dashboard', 'volume-high',
                        'wallet', 'web', 'whatsapp', 'wifi', 'wrench',
                        'youtube', 'zip-box'
                    ],
                    fa: [
                        'house', 'user', 'users', 'gear', 'magnifying-glass',
                        'envelope', 'phone', 'location-dot', 'calendar', 'clock',
                        'bell', 'heart', 'star', 'bookmark', 'comment',
                        'share', 'link', 'image', 'file', 'folder',
                        'download', 'upload', 'print', 'trash', 'pen',
                        'check', 'xmark', 'plus', 'minus', 'circle-info',
                        'triangle-exclamation', 'circle-check', 'circle-xmark', 'arrow-right', 'arrow-left',
                        'arrow-up', 'arrow-down', 'chevron-right', 'chevron-left', 'chevron-up',
                        'chevron-down', 'angles-right', 'angles-left', 'bars', 'grip-lines',
                        'cart-shopping', 'bag-shopping', 'credit-card', 'money-bill', 'wallet',
                        'chart-line', 'chart-bar', 'chart-pie', 'database', 'server',
                        'lock', 'lock-open', 'key', 'shield-halved', 'eye',
                        'eye-slash', 'globe', 'language', 'palette', 'brush',
                        'code', 'laptop', 'desktop', 'mobile', 'tablet',
                        'tv', 'camera', 'video', 'music', 'headphones',
                        'microphone', 'volume-high', 'play', 'pause', 'stop',
                        'forward', 'backward', 'shuffle', 'repeat', 'list',
                        'table', 'grip', 'border-all', 'filter', 'sort',
                        'bolt', 'fire', 'sun', 'moon', 'cloud',
                        'snowflake', 'umbrella', 'tree', 'leaf', 'seedling',
                        'gift', 'trophy', 'medal', 'crown', 'gem',
                        'rocket', 'paperclip', 'thumbtack', 'tag', 'tags',
                        'flag', 'map', 'compass', 'route',
                        'car', 'bus', 'train', 'plane', 'ship',
                        'bicycle', 'motorcycle', 'helicopter', 'building', 'hospital',
                        'school', 'graduation-cap', 'book', 'book-open', 'newspaper',
                        'lightbulb', 'plug', 'battery-full', 'signal', 'wifi',
                        'qrcode', 'barcode', 'fingerprint', 'id-card', 'address-card'
                    ]
                };

                const sharedState = {
                    activeInputId: null,
                    selectedIcon: '',
                    inited: {}
                };

                function mountModalToBody(modal) {
                    if (modal && modal.parentElement !== document.body) {
                        document.body.appendChild(modal);
                    }
                }

                function buildIconList(filter, search) {
                    let icons = [];
                    if (filter === 'all' || filter === 'mdi') {
                        iconLibrary.mdi.forEach(function(icon) {
                            icons.push({ class: 'mdi mdi-' + icon, name: icon });
                        });
                    }
                    if (filter === 'all' || filter === 'fa') {
                        iconLibrary.fa.forEach(function(icon) {
                            icons.push({ class: 'fa-solid fa-' + icon, name: icon });
                        });
                    }
                    if (search) {
                        const searchLower = search.toLowerCase();
                        icons = icons.filter(function(icon) {
                            return icon.name.toLowerCase().indexOf(searchLower) !== -1;
                        });
                    }
                    return icons;
                }

                function renderIconsInto(grid, loading, selectedIcon, filter, search, onSelect) {
                    if (!grid) {
                        return;
                    }
                    grid.innerHTML = '';
                    if (loading) {
                        loading.style.display = 'block';
                    }
                    window.setTimeout(function() {
                        const icons = buildIconList(filter || 'all', search || '');
                        icons.forEach(function(icon) {
                            const item = document.createElement('div');
                            item.className = 'icon-grid-item' + (icon.class === selectedIcon ? ' selected' : '');
                            item.dataset.icon = icon.class;
                            item.innerHTML = '<i class="' + icon.class + '"></i><span class="icon-name">' + icon.name + '</span>';
                            item.addEventListener('click', function() {
                                grid.querySelectorAll('.icon-grid-item').forEach(function(node) {
                                    node.classList.toggle('selected', node.dataset.icon === icon.class);
                                });
                                if (typeof onSelect === 'function') {
                                    onSelect(icon.class);
                                }
                            });
                            grid.appendChild(item);
                        });
                        if (loading) {
                            loading.style.display = 'none';
                        }
                    }, 50);
                }

                function updateInputPreview(inputId, iconClass) {
                    const inputField = document.getElementById(inputId);
                    const previewSpan = document.getElementById('preview-' + inputId);
                    if (!inputField) {
                        return;
                    }
                    inputField.value = iconClass || '';
                    if (previewSpan) {
                        previewSpan.innerHTML = iconClass ?
                            '<i class="' + iconClass + '"></i>' :
                            '<i class="mdi mdi-emoticon-outline"></i>';
                    }
                }

                function initStandaloneIconPicker(inputId) {
                    const inputField = document.getElementById(inputId);
                    if (!inputField || inputField.dataset.iconPickerInit === '1') {
                        return;
                    }
                    const modal = document.getElementById('iconPickerModal-' + inputId);
                    if (!modal) {
                        return;
                    }

                    inputField.dataset.iconPickerInit = '1';
                    mountModalToBody(modal);

                    const grid = document.getElementById('iconGrid-' + inputId);
                    const loading = document.getElementById('iconLoading-' + inputId);
                    const searchInput = modal.querySelector('.icon-search');
                    const libraryFilter = modal.querySelector('.icon-library-filter');
                    const selectedDisplay = document.getElementById('selectedIconDisplay-' + inputId);
                    let selectedIcon = inputField.value || '';

                    function updateSelectedDisplay(iconClass) {
                        if (!selectedDisplay) {
                            return;
                        }
                        selectedDisplay.innerHTML = iconClass ?
                            '<i class="' + iconClass + '"></i><code class="ms-2">' + iconClass + '</code>' :
                            '<i class="mdi mdi-emoticon-outline"></i><code class="ms-2">{{ __('None') }}</code>';
                    }

                    function render(filter, search) {
                        renderIconsInto(grid, loading, selectedIcon, filter, search, function(iconClass) {
                            selectedIcon = iconClass;
                            updateSelectedDisplay(iconClass);
                        });
                    }

                    modal.querySelector('.confirm-icon-btn').addEventListener('click', function() {
                        updateInputPreview(inputId, selectedIcon);
                    });

                    if (searchInput) {
                        searchInput.addEventListener('input', function(e) {
                            render(libraryFilter.value, e.target.value);
                        });
                    }
                    if (libraryFilter) {
                        libraryFilter.addEventListener('change', function(e) {
                            render(e.target.value, searchInput ? searchInput.value : '');
                        });
                    }

                    modal.addEventListener('show.bs.modal', function() {
                        selectedIcon = inputField.value || '';
                        updateSelectedDisplay(selectedIcon);
                        render('all', '');
                    });
                }

                function initSharedIconPicker(modalId) {
                    if (sharedState.inited[modalId]) {
                        return;
                    }
                    const modal = document.getElementById('iconPickerModal-' + modalId);
                    if (!modal) {
                        return;
                    }

                    sharedState.inited[modalId] = true;
                    mountModalToBody(modal);

                    const grid = modal.querySelector('[data-role="icon-picker-grid"]');
                    const loading = modal.querySelector('[data-role="icon-picker-loading"]');
                    const searchInput = modal.querySelector('[data-role="icon-picker-search"]');
                    const libraryFilter = modal.querySelector('[data-role="icon-picker-library-filter"]');
                    const selectedDisplay = modal.querySelector('[data-role="icon-picker-selected-display"]');
                    const confirmBtn = modal.querySelector('[data-role="icon-picker-confirm"]');

                    function updateSelectedDisplay(iconClass) {
                        if (!selectedDisplay) {
                            return;
                        }
                        selectedDisplay.innerHTML = iconClass ?
                            '<i class="' + iconClass + '"></i><code class="ms-2">' + iconClass + '</code>' :
                            '<i class="mdi mdi-emoticon-outline"></i><code class="ms-2">{{ __('None') }}</code>';
                    }

                    function render(filter, search) {
                        renderIconsInto(grid, loading, sharedState.selectedIcon, filter, search, function(iconClass) {
                            sharedState.selectedIcon = iconClass;
                            updateSelectedDisplay(iconClass);
                        });
                    }

                    if (searchInput) {
                        searchInput.addEventListener('input', function(e) {
                            render(libraryFilter.value, e.target.value);
                        });
                    }
                    if (libraryFilter) {
                        libraryFilter.addEventListener('change', function(e) {
                            render(e.target.value, searchInput ? searchInput.value : '');
                        });
                    }

                    modal.addEventListener('show.bs.modal', function() {
                        const inputField = sharedState.activeInputId ?
                            document.getElementById(sharedState.activeInputId) : null;
                        sharedState.selectedIcon = inputField ? (inputField.value || '') : '';
                        updateSelectedDisplay(sharedState.selectedIcon);
                        render('all', '');
                    });

                    if (confirmBtn) {
                        confirmBtn.addEventListener('click', function() {
                            if (sharedState.activeInputId) {
                                updateInputPreview(sharedState.activeInputId, sharedState.selectedIcon);
                            }
                        });
                    }
                }

                function openSharedIconPicker(modalId, inputId) {
                    initSharedIconPicker(modalId);
                    sharedState.activeInputId = inputId;
                    const modal = document.getElementById('iconPickerModal-' + modalId);
                    if (!modal || !window.bootstrap) {
                        return;
                    }
                    window.bootstrap.Modal.getOrCreateInstance(modal).show();
                }

                window.initIconPicker = initStandaloneIconPicker;
                window.initSharedIconPicker = initSharedIconPicker;
                window.openSharedIconPicker = openSharedIconPicker;

                document.addEventListener('click', function(e) {
                    if (e.target.closest('.clear-icon-btn')) {
                        const btn = e.target.closest('.clear-icon-btn');
                        updateInputPreview(btn.dataset.input, '');
                        return;
                    }

                    let trigger = e.target.closest('[data-role="icon-picker-trigger"]');
                    if (!trigger) {
                        const preview = e.target.closest('.icon-picker-wrapper .icon-preview');
                        if (preview) {
                            trigger = preview.closest('.icon-picker-wrapper') ?
                                preview.closest('.icon-picker-wrapper').querySelector('[data-role="icon-picker-trigger"]') :
                                null;
                        }
                    }
                    if (!trigger || !trigger.dataset.sharedModal) {
                        return;
                    }
                    e.preventDefault();
                    openSharedIconPicker(trigger.dataset.sharedModal, trigger.id);
                });

                document.addEventListener('shown.bs.modal', function(e) {
                    if (e.target.classList.contains('icon-picker-modal')) {
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.classList.add('icon-picker-backdrop');
                        }
                    }
                });

                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.icon-picker-input').forEach(function(input) {
                        if (input.id && !input.dataset.sharedModal) {
                            initStandaloneIconPicker(input.id);
                        }
                    });
                    document.querySelectorAll('[data-role="icon-picker-shared-modal"]').forEach(function(modal) {
                        const modalId = modal.id.replace('iconPickerModal-', '');
                        initSharedIconPicker(modalId);
                    });
                });
            })();
        </script>
    @endpush
@endonce
