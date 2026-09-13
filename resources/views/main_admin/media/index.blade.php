@extends('layout_new.mainlayout')

@push('styles')
<style>
	.wm-page { padding: 20px; }
	.wm-filters .form-label { font-size: 12px; font-weight: 600; color: #5b6b7c; margin-bottom: 4px; }
	.wm-card {
		background: #fff;
		border: 1px solid rgba(16, 24, 40, .08);
		border-radius: 16px;
		overflow: hidden;
		height: 100%;
		display: flex;
		flex-direction: column;
		box-shadow: 0 8px 24px rgba(16, 24, 40, .05);
		transition: transform .2s ease, box-shadow .2s ease;
	}
	.wm-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px rgba(16, 24, 40, .1); }
	.wm-card__preview {
		position: relative;
		height: 180px;
		background: #f3f5f8;
		overflow: hidden;
	}
	.wm-card__preview img,
	.wm-card__preview video {
		width: 100%;
		height: 100%;
		object-fit: cover;
		display: block;
	}
	.wm-card__placeholder {
		height: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		color: #8b97a6;
		gap: 6px;
	}
	.wm-card__placeholder i { font-size: 32px; }
	.wm-card__badge {
		position: absolute;
		top: 10px;
		inset-inline-start: 10px;
		font-size: 11px;
		font-weight: 600;
		padding: 4px 8px;
		border-radius: 999px;
		background: #fff;
		color: #2c3e50;
		box-shadow: 0 4px 10px rgba(16, 24, 40, .12);
	}
	.wm-card__warn {
		position: absolute;
		top: 10px;
		inset-inline-end: 10px;
		width: 28px;
		height: 28px;
		border-radius: 50%;
		background: #fff4e5;
		color: #d97706;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.wm-card__body { padding: 14px 14px 16px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
	.wm-card__title {
		font-size: 14px;
		font-weight: 700;
		margin: 0;
		color: #1f2a37;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
		min-height: 38px;
	}
	.wm-card__sub { font-size: 12px; color: #6b7280; margin: 0; }
	.wm-card__meta {
		display: flex;
		flex-direction: column;
		gap: 4px;
		font-size: 12px;
		color: #4b5563;
		background: #f8fafc;
		border: 1px solid rgba(16, 24, 40, .06);
		border-radius: 10px;
		padding: 8px 10px;
	}
	.wm-card__meta strong { color: #1f2a37; font-weight: 600; }
	.wm-card__meta span { display: block; line-height: 1.35; }
	.wm-hint {
		background: #eef6ff;
		border: 1px dashed #9ec5fe;
		color: #1d4ed8;
		border-radius: 10px;
		padding: 8px 10px;
		font-size: 12px;
		line-height: 1.4;
	}
	.wm-hint strong { display: block; font-size: 12px; }
	.wm-current { font-size: 12px; color: #4b5563; }
	.wm-current.is-mismatch { color: #b45309; }
	.wm-card__actions { display: flex; gap: 8px; margin-top: auto; flex-wrap: wrap; }
	.wm-card__actions .btn { flex: 1; }
	.wm-card__actions .wm-convert-btn { flex: 1 1 100%; }
	.wm-empty {
		padding: 60px 20px;
		text-align: center;
		color: #6b7280;
	}
	.wm-count { font-size: 12px; color: #6b7280; }
	.wm-modal-preview img,
	.wm-modal-preview video {
		max-height: 180px;
		max-width: 100%;
		object-fit: contain;
		border-radius: 10px;
	}
</style>
@endpush

@section('content')
<div class="page-wrapper wm-page">
	<div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
		<div>
			<h4 class="fw-bold mb-1">{{ __('website_media.title') }}</h4>
			<p class="text-muted mb-0">{{ __('website_media.subtitle') }}</p>
		</div>
		<div class="wm-count" id="wmShowing"></div>
	</div>

	<div class="card mb-3 wm-filters">
		<div class="card-body">
			<div class="row g-3 align-items-end">
				<div class="col-md-4 col-lg-3">
					<label class="form-label" for="wmSearch">{{ __('website_media.search_placeholder') }}</label>
					<input type="search" id="wmSearch" class="form-control" placeholder="{{ __('website_media.search_placeholder') }}">
				</div>
				<div class="col-md-4 col-lg-2">
					<label class="form-label" for="wmSource">{{ __('website_media.source') }}</label>
					<select id="wmSource" class="form-select">
						<option value="">{{ __('website_media.all_sources') }}</option>
						<option value="theme">{{ __('website_media.theme') }}</option>
						<option value="cms">{{ __('website_media.cms') }}</option>
						<option value="blogs">{{ __('website_media.blogs') }}</option>
						<option value="blog_categories">{{ __('website_media.blog_categories') }}</option>
						<option value="clinics">{{ __('website_media.clinics') }}</option>
						<option value="doctors">{{ __('website_media.doctors') }}</option>
					</select>
				</div>
				<div class="col-md-4 col-lg-2">
					<label class="form-label" for="wmStatus">{{ __('website_media.status') }}</label>
					<select id="wmStatus" class="form-select">
						<option value="">{{ __('website_media.all_statuses') }}</option>
						<option value="active">{{ __('website_media.active') }}</option>
						<option value="inactive">{{ __('website_media.inactive') }}</option>
					</select>
				</div>
				<div class="col-md-4 col-lg-2">
					<label class="form-label" for="wmHasImage">{{ __('website_media.image_status') }}</label>
					<select id="wmHasImage" class="form-select">
						<option value="">{{ __('website_media.all_images') }}</option>
						<option value="1">{{ __('website_media.with_image') }}</option>
						<option value="missing">{{ __('website_media.missing_image') }}</option>
					</select>
				</div>
				<div class="col-md-4 col-lg-2">
					<label class="form-label" for="wmPerPage">{{ __('website_media.per_page') }}</label>
					<select id="wmPerPage" class="form-select">
						<option value="12">12</option>
						<option value="24" selected>24</option>
						<option value="48">48</option>
					</select>
				</div>
			</div>
			<div class="d-flex flex-wrap gap-2 mt-3" id="wmCounts"></div>
		</div>
	</div>

	<div id="wmGrid" class="row g-3"></div>
	<div id="wmEmpty" class="card wm-empty d-none">{{ __('website_media.empty') }}</div>
	<nav class="mt-4">
		<ul class="pagination justify-content-center" id="wmPagination"></ul>
	</nav>
</div>

<div class="modal fade" id="wmEditModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form id="wmEditForm" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="key" id="wmEditKey">
				<div class="modal-header">
					<h5 class="modal-title">{{ __('website_media.update_media') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<h6 class="mb-1" id="wmEditTitle"></h6>
						<p class="text-muted mb-0" id="wmEditSubtitle"></p>
					</div>
					<div class="wm-hint mb-3" id="wmEditHint"></div>
					<div class="mb-3">
						<label class="form-label">{{ __('website_media.current_size') }}</label>
						<div class="wm-modal-preview border rounded p-3 text-center bg-light" id="wmEditCurrent"></div>
					</div>
					<div class="mb-3">
						<label class="form-label" for="wmEditFile">{{ __('website_media.replace_file') }}</label>
						<input type="file" class="form-control" name="file" id="wmEditFile" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml,video/mp4,video/webm">
						<small class="text-muted">{{ __('website_media.leave_hint') }}</small>
					</div>
					<div class="mb-0 d-none" id="wmEditNewWrap">
						<label class="form-label">{{ __('website_media.new_preview') }}</label>
						<div class="wm-modal-preview border rounded p-3 text-center" id="wmEditNew"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-warning d-none" id="wmEditConvert">
						<i class="ti ti-crop me-1"></i>{{ __('website_media.convert') }}
					</button>
					<button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('website_media.cancel') }}</button>
					<button type="submit" class="btn btn-primary" id="wmEditSave">{{ __('website_media.save') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
	const i18n = {
		recommended: @json(__('website_media.recommended_size')),
		current: @json(__('website_media.current_size')),
		unknown: @json(__('website_media.unknown_size')),
		mismatch: @json(__('website_media.size_mismatch')),
		noImage: @json(__('website_media.no_image')),
		update: @json(__('website_media.update')),
		view: @json(__('website_media.view')),
		fileSize: @json(__('website_media.file_size')),
		showing: @json(__('website_media.showing')),
		selectFile: @json(__('website_media.select_file')),
		uploading: @json(__('website_media.uploading')),
		save: @json(__('website_media.save')),
		loadError: @json(__('website_media.load_error')),
		convert: @json(__('website_media.convert')),
		convertTo: @json(__('website_media.convert_to')),
		converting: @json(__('website_media.converting')),
		convertTitle: @json(__('website_media.convert_confirm_title')),
		convertText: @json(__('website_media.convert_confirm_text')),
		convertButton: @json(__('website_media.convert_confirm_button')),
		cancel: @json(__('website_media.cancel')),
		page: @json(__('website_media.page')),
		section: @json(__('website_media.section')),
		sources: {
			all: @json(__('website_media.all_sources')),
			theme: @json(__('website_media.theme')),
			cms: @json(__('website_media.cms')),
			blogs: @json(__('website_media.blogs')),
			blog_categories: @json(__('website_media.blog_categories')),
			clinics: @json(__('website_media.clinics')),
			doctors: @json(__('website_media.doctors'))
		}
	};

	let page = 1;
	let lastMeta = null;
	let itemsByKey = {};
	const modalEl = document.getElementById('wmEditModal');
	const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

	function escapeHtml(value) {
		if (value === null || value === undefined) return '';
		return String(value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function sourceLabel(source) {
		return i18n.sources[source] || source;
	}

	function renderCounts(counts) {
		const keys = ['all', 'theme', 'cms', 'blogs', 'blog_categories', 'clinics', 'doctors'];
		const current = $('#wmSource').val() || 'all';
		$('#wmCounts').html(keys.map(function (key) {
			const value = key === 'all' ? (counts.all || 0) : (counts[key] || 0);
			const active = current === key || (key === 'all' && current === '');
			return '<button type="button" class="btn btn-sm ' + (active ? 'btn-primary' : 'btn-outline-secondary') + '" data-source="' + (key === 'all' ? '' : key) + '">' +
				escapeHtml(sourceLabel(key)) + ' <span class="badge bg-white text-dark ms-1">' + value + '</span></button>';
		}).join(''));
	}

	function previewHtml(item, compact) {
		if (item.is_video && item.url) {
			return '<video src="' + escapeHtml(item.url) + '" muted></video>';
		}
		if (item.has_image && item.url) {
			return '<img src="' + escapeHtml(item.url) + '" alt="' + escapeHtml(item.title) + '">';
		}
		return '<div class="wm-card__placeholder"><i class="ti ti-photo-off"></i><span>' + escapeHtml(i18n.noImage) + '</span></div>';
	}

	function cardHtml(item) {
		const mismatch = item.size_mismatch
			? '<span class="wm-card__warn" title="' + escapeHtml(i18n.mismatch) + '"><i class="ti ti-alert-triangle"></i></span>'
			: '';
		const current = item.current_size
			? '<div class="wm-current ' + (item.size_mismatch ? 'is-mismatch' : '') + '">' + escapeHtml(i18n.current) + ': <strong>' + escapeHtml(item.current_size.label) + '</strong>' + (item.file_size ? ' · ' + escapeHtml(item.file_size) : '') + '</div>'
			: '<div class="wm-current">' + escapeHtml(item.has_image ? i18n.unknown : i18n.noImage) + '</div>';
		const viewBtn = item.url
			? '<a class="btn btn-sm btn-outline-secondary" href="' + escapeHtml(item.url) + '" target="_blank"><i class="ti ti-eye me-1"></i>' + escapeHtml(i18n.view) + '</a>'
			: '';
		const convertLabel = i18n.convertTo.replace(':size', item.hint && item.hint.label ? item.hint.label : '');
		const convertBtn = item.can_convert
			? '<button type="button" class="btn btn-sm ' + (item.size_mismatch ? 'btn-warning' : 'btn-outline-warning') + ' wm-convert-btn" data-key="' + escapeHtml(item.key) + '">' +
				'<i class="ti ti-crop me-1"></i>' + escapeHtml(convertLabel) +
			'</button>'
			: '';
		const cmsMeta = (item.source === 'cms' && (item.page_name || item.section_name))
			? '<div class="wm-card__meta">' +
				(item.page_name ? '<span><strong>' + escapeHtml(i18n.page) + ':</strong> ' + escapeHtml(item.page_name) + '</span>' : '') +
				(item.section_name ? '<span><strong>' + escapeHtml(i18n.section) + ':</strong> ' + escapeHtml(item.section_name) + '</span>' : '') +
			'</div>'
			: '<p class="wm-card__sub">' + escapeHtml(item.subtitle || '') + '</p>';

		return '<div class="col-sm-6 col-lg-4 col-xxl-3">' +
			'<article class="wm-card">' +
				'<div class="wm-card__preview">' + previewHtml(item) +
					'<span class="wm-card__badge">' + escapeHtml(sourceLabel(item.source)) + '</span>' + mismatch +
				'</div>' +
				'<div class="wm-card__body">' +
					'<h6 class="wm-card__title" title="' + escapeHtml(item.title) + '">' + escapeHtml(item.title) + '</h6>' +
					cmsMeta +
					'<div class="wm-hint"><strong>' + escapeHtml(i18n.recommended) + ': ' + escapeHtml(item.hint.label) + '</strong>' + escapeHtml(item.hint.note || '') + '</div>' +
					current +
					'<div class="wm-card__actions">' +
						'<button type="button" class="btn btn-sm btn-primary wm-update-btn" data-key="' + escapeHtml(item.key) + '">' +
							'<i class="ti ti-upload me-1"></i>' + escapeHtml(i18n.update) +
						'</button>' + viewBtn + convertBtn +
					'</div>' +
				'</div>' +
			'</article></div>';
	}

	function renderPagination(meta) {
		const $ul = $('#wmPagination').empty();
		if (!meta || meta.last_page <= 1) return;
		function pageItem(label, target, disabled, active) {
			return '<li class="page-item ' + (disabled ? 'disabled' : '') + ' ' + (active ? 'active' : '') + '">' +
				'<a class="page-link" href="#" data-page="' + target + '">' + label + '</a></li>';
		}
		$ul.append(pageItem('«', Math.max(1, meta.current_page - 1), meta.current_page === 1, false));
		const start = Math.max(1, meta.current_page - 2);
		const end = Math.min(meta.last_page, meta.current_page + 2);
		for (let i = start; i <= end; i++) {
			$ul.append(pageItem(String(i), i, false, i === meta.current_page));
		}
		$ul.append(pageItem('»', Math.min(meta.last_page, meta.current_page + 1), meta.current_page === meta.last_page, false));
	}

	function loadMedia() {
		$('#wmGrid').html('<div class="col-12 text-center py-5 text-muted"><div class="spinner-border spinner-border-sm me-2"></div></div>');
		$.ajax({
			url: @json(route('website-media.data')),
			data: {
				page: page,
				per_page: $('#wmPerPage').val(),
				source: $('#wmSource').val(),
				status: $('#wmStatus').val(),
				has_image: $('#wmHasImage').val(),
				search: $('#wmSearch').val()
			},
			success: function (res) {
				lastMeta = res.meta;
				renderCounts(res.counts || {});
				if (!res.data.length) {
					$('#wmGrid').empty();
					$('#wmEmpty').removeClass('d-none');
				} else {
					$('#wmEmpty').addClass('d-none');
					itemsByKey = {};
					res.data.forEach(function (item) { itemsByKey[item.key] = item; });
					$('#wmGrid').html(res.data.map(cardHtml).join(''));
				}
				if (res.meta && res.meta.total) {
					$('#wmShowing').text(
						i18n.showing
							.replace(':from', res.meta.from || 0)
							.replace(':to', res.meta.to || 0)
							.replace(':total', res.meta.total || 0)
					);
				} else {
					$('#wmShowing').text('');
				}
				renderPagination(res.meta);
			},
			error: function () {
				$('#wmGrid').html('<div class="col-12"><div class="alert alert-danger mb-0">' + escapeHtml(i18n.loadError) + '</div></div>');
			}
		});
	}

	function openEdit(item) {
		$('#wmEditKey').val(item.key);
		$('#wmEditTitle').text(item.title || '');
		$('#wmEditSubtitle').text(item.subtitle || '');
		$('#wmEditHint').html('<strong>' + escapeHtml(i18n.recommended) + ': ' + escapeHtml(item.hint.label) + '</strong>' + escapeHtml(item.hint.note || ''));
		let current = previewHtml(item);
		if (item.current_size) {
			current += '<div class="mt-2 small">' + escapeHtml(i18n.current) + ': ' + escapeHtml(item.current_size.label) + (item.file_size ? ' · ' + escapeHtml(item.file_size) : '') + '</div>';
		}
		$('#wmEditCurrent').html(current);
		$('#wmEditFile').val('');
		$('#wmEditNewWrap').addClass('d-none');
		$('#wmEditNew').empty();
		if (item.can_convert) {
			$('#wmEditConvert')
				.removeClass('d-none')
				.data('key', item.key)
				.html('<i class="ti ti-crop me-1"></i>' + i18n.convertTo.replace(':size', item.hint && item.hint.label ? item.hint.label : ''));
		} else {
			$('#wmEditConvert').addClass('d-none').removeData('key');
		}
		modal.show();
	}

	$('#wmSource, #wmStatus, #wmHasImage, #wmPerPage').on('change', function () {
		page = 1;
		loadMedia();
	});

	let searchTimer = null;
	$('#wmSearch').on('keyup', function () {
		clearTimeout(searchTimer);
		searchTimer = setTimeout(function () {
			page = 1;
			loadMedia();
		}, 350);
	});

	$(document).on('click', '#wmCounts [data-source]', function () {
		$('#wmSource').val($(this).data('source'));
		page = 1;
		loadMedia();
	});

	$(document).on('click', '#wmPagination .page-link', function (e) {
		e.preventDefault();
		const next = parseInt($(this).data('page'), 10);
		if (!next || (lastMeta && next === lastMeta.current_page)) return;
		page = next;
		loadMedia();
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});

	$(document).on('click', '.wm-update-btn', function () {
		const item = itemsByKey[$(this).data('key')];
		if (item) openEdit(item);
	});

	function convertMedia(key) {
		const item = itemsByKey[key];
		if (!item) return;
		const size = item.hint && item.hint.label ? item.hint.label : '';
		Swal.fire({
			icon: 'question',
			title: i18n.convertTitle,
			text: i18n.convertText.replace(':size', size),
			showCancelButton: true,
			confirmButtonText: i18n.convertButton,
			cancelButtonText: i18n.cancel
		}).then(function (result) {
			if (!result.isConfirmed) return;
			Swal.fire({
				title: i18n.converting,
				allowOutsideClick: false,
				didOpen: function () { Swal.showLoading(); }
			});
			$.ajax({
				url: @json(route('website-media.convert')),
				type: 'POST',
				data: {
					_token: @json(csrf_token()),
					key: key
				},
				success: function (res) {
					if (typeof modal !== 'undefined') {
						modal.hide();
					}
					loadMedia();
					Swal.fire({ icon: 'success', text: res.message });
				},
				error: function (xhr) {
					Swal.fire({
						icon: 'error',
						text: (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : i18n.loadError
					});
				}
			});
		});
	}

	$(document).on('click', '.wm-convert-btn', function () {
		convertMedia($(this).data('key'));
	});

	$('#wmEditConvert').on('click', function () {
		const key = $(this).data('key') || $('#wmEditKey').val();
		if (key) convertMedia(key);
	});

	$('#wmEditFile').on('change', function () {
		const file = this.files[0];
		if (!file) {
			$('#wmEditNewWrap').addClass('d-none');
			return;
		}
		const url = URL.createObjectURL(file);
		const isImage = file.type.indexOf('image/') === 0;
		$('#wmEditNew').html(isImage
			? '<img src="' + url + '" alt="">'
			: '<video src="' + url + '" muted></video>'
		);
		$('#wmEditNewWrap').removeClass('d-none');
	});

	$('#wmEditForm').on('submit', function (e) {
		e.preventDefault();
		if (!$('#wmEditFile')[0].files.length) {
			Swal.fire({ icon: 'warning', text: i18n.selectFile });
			return;
		}
		const formData = new FormData(this);
		const $btn = $('#wmEditSave').prop('disabled', true).text(i18n.uploading);
		$.ajax({
			url: @json(route('website-media.update')),
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function (res) {
				modal.hide();
				loadMedia();
				Swal.fire({ icon: 'success', text: res.message });
			},
			error: function (xhr) {
				Swal.fire({
					icon: 'error',
					text: (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : i18n.loadError
				});
			},
			complete: function () {
				$btn.prop('disabled', false).text(i18n.save);
			}
		});
	});

	loadMedia();
});
</script>
@endpush
