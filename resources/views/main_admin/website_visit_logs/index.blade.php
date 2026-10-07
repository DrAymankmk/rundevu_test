@extends('layout_new.mainlayout')

@push('styles')
<style>
.visit-event-timeline { display: flex; flex-direction: column; gap: 12px; max-height: 520px; overflow: auto; padding-inline-end: 4px; }
.visit-event-item { display: flex; gap: 12px; align-items: flex-start; padding: 12px; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 12px; background: #fff; }
.visit-event-icon { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 36px; color: #fff; font-size: 16px; }
.visit-event-item--primary .visit-event-icon { background: #3E66F3; }
.visit-event-item--success .visit-event-icon { background: #198754; }
.visit-event-item--info .visit-event-icon { background: #0dcaf0; color: #083344; }
.visit-event-item--warning .visit-event-icon { background: #ffc107; color: #3a2d00; }
.visit-event-item--secondary .visit-event-icon { background: #6c757d; }
.visit-event-body { min-width: 0; flex: 1; }
.visit-event-details { padding: 0; margin: 0; list-style: none; font-size: 12px; }
.visit-event-details li + li { margin-top: 2px; }
</style>
@endpush

@section('content')
<div class="page-wrapper" style="padding:20px">
	<div class="row mb-3">
		<div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
			<div>
				<h4 class="page-title mb-1">{{ __('website_visit_logs.title') }}</h4>
				<p class="text-muted mb-0">{{ __('website_visit_logs.subtitle') }}</p>
			</div>
			<button type="button" id="bulk-delete-btn" class="btn btn-danger" disabled>
				<i class="ti ti-trash"></i> {{ __('website_visit_logs.bulk_delete') }}
			</button>
		</div>
	</div>

	@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif

	<div class="row g-3 mb-3" id="stats-cards">
		<div class="col-6 col-md-4 col-xl">
			<div class="card"><div class="card-body">
				<div class="text-muted small">{{ __('website_visit_logs.stats_visits') }}</div>
				<div class="fw-semibold" data-stat="visits">{{ $stats['totals']['visits'] }}</div>
			</div></div>
		</div>
		<div class="col-6 col-md-4 col-xl">
			<div class="card"><div class="card-body">
				<div class="text-muted small">{{ __('website_visit_logs.stats_unique_ips') }}</div>
				<div class="fw-semibold" data-stat="unique_ips">{{ $stats['totals']['unique_ips'] }}</div>
			</div></div>
		</div>
		<div class="col-6 col-md-4 col-xl">
			<div class="card"><div class="card-body">
				<div class="text-muted small">{{ __('website_visit_logs.stats_humans') }}</div>
				<div class="fw-semibold" data-stat="humans">{{ $stats['totals']['humans'] }}</div>
			</div></div>
		</div>
		<div class="col-6 col-md-4 col-xl">
			<div class="card"><div class="card-body">
				<div class="text-muted small">{{ __('website_visit_logs.stats_bots') }}</div>
				<div class="fw-semibold" data-stat="bots">{{ $stats['totals']['bots'] }}</div>
			</div></div>
		</div>
		<div class="col-6 col-md-4 col-xl">
			<div class="card"><div class="card-body">
				<div class="text-muted small">{{ __('website_visit_logs.stats_avg_time') }}</div>
				<div class="fw-semibold" data-stat="avg_time_spent">{{ $stats['totals']['avg_time_spent'] }}{{ __('website_visit_logs.seconds') }}</div>
			</div></div>
		</div>
	</div>

	<div class="row g-3 mb-3">
		<div class="col-lg-8">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="mb-3">{{ __('website_visit_logs.visits_over_time') }}</h6>
					<canvas id="visitsChart" height="120"></canvas>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="mb-3">{{ __('website_visit_logs.most_visited_pages') }}</h6>
					<ul class="list-group list-group-flush" id="stat-pages">
						@forelse($stats['pages'] as $row)
							<li class="list-group-item d-flex justify-content-between px-0">
								<span class="text-truncate me-2">{{ $row['label'] }}</span>
								<span class="badge bg-primary">{{ $row['total'] }}</span>
							</li>
						@empty
							<li class="list-group-item px-0 text-muted">{{ __('website_visit_logs.no_data') }}</li>
						@endforelse
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mb-3">
		@foreach([
			['key' => 'doctors', 'title' => __('website_visit_logs.most_visited_doctors')],
			['key' => 'clinics', 'title' => __('website_visit_logs.most_visited_clinics')],
			['key' => 'blog_posts', 'title' => __('website_visit_logs.most_visited_blog')],
			['key' => 'referrers', 'title' => __('website_visit_logs.top_referrers')],
			['key' => 'links', 'title' => __('website_visit_logs.top_links')],
		] as $block)
		<div class="col-md-6 col-xl">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="mb-3">{{ $block['title'] }}</h6>
					<ul class="list-group list-group-flush" id="stat-{{ $block['key'] }}">
						@forelse($stats[$block['key']] as $row)
							<li class="list-group-item d-flex justify-content-between px-0">
								<span class="text-truncate me-2">{{ $row['label'] }}</span>
								<span class="badge bg-secondary">{{ $row['total'] }}</span>
							</li>
						@empty
							<li class="list-group-item px-0 text-muted">{{ __('website_visit_logs.no_data') }}</li>
						@endforelse
					</ul>
				</div>
			</div>
		</div>
		@endforeach
	</div>

	<div class="card mb-3">
		<div class="card-body">
			<h6 class="mb-3">{{ __('website_visit_logs.filters') }}</h6>
			<form id="visit-filters" class="row g-2">
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.date_from') }}</label>
					<input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.date_to') }}</label>
					<input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.visitor_type') }}</label>
					<select name="visitor_type" class="form-select">
						<option value="all" {{ ($filters['visitor_type'] ?? '') === 'all' ? 'selected' : '' }}>{{ __('website_visit_logs.all') }}</option>
						<option value="human" {{ ($filters['visitor_type'] ?? '') === 'human' ? 'selected' : '' }}>{{ __('website_visit_logs.human') }}</option>
						<option value="bot" {{ ($filters['visitor_type'] ?? '') === 'bot' ? 'selected' : '' }}>{{ __('website_visit_logs.bot') }}</option>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.bot_name') }}</label>
					<input type="text" name="bot_name" class="form-control" value="{{ $filters['bot_name'] }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.locale') }}</label>
					<select name="locale" class="form-select">
						<option value="">{{ __('website_visit_logs.all') }}</option>
						<option value="en" {{ ($filters['locale'] ?? '') === 'en' ? 'selected' : '' }}>EN</option>
						<option value="ar" {{ ($filters['locale'] ?? '') === 'ar' ? 'selected' : '' }}>AR</option>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.has_events') }}</label>
					<select name="has_events" class="form-select">
						<option value="">{{ __('website_visit_logs.any') }}</option>
						<option value="yes" {{ ($filters['has_events'] ?? '') === 'yes' ? 'selected' : '' }}>{{ __('website_visit_logs.yes') }}</option>
						<option value="no" {{ ($filters['has_events'] ?? '') === 'no' ? 'selected' : '' }}>{{ __('website_visit_logs.no') }}</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">{{ __('website_visit_logs.page_type') }}</label>
					<select name="page_type[]" class="form-select" multiple size="3">
						@foreach($pageTypes as $type)
							<option value="{{ $type }}" {{ in_array($type, (array) ($filters['page_type'] ?? []), true) ? 'selected' : '' }}>
								{{ __('website_visit_logs.page_types.' . $type) }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">{{ __('website_visit_logs.page_path') }}</label>
					<input type="text" name="page_path" class="form-control" value="{{ $filters['page_path'] }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.entity_type') }}</label>
					<select name="entity_type" class="form-select">
						<option value="">{{ __('website_visit_logs.all') }}</option>
						@foreach(['doctor','clinic','blog_post','specialty','website_link'] as $etype)
							<option value="{{ $etype }}" {{ ($filters['entity_type'] ?? '') === $etype ? 'selected' : '' }}>
								{{ __('website_visit_logs.entity_types.' . $etype) }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.entity_slug') }}</label>
					<input type="text" name="entity_slug" class="form-control" value="{{ $filters['entity_slug'] }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.ip') }}</label>
					<input type="text" name="ip" class="form-control" value="{{ $filters['ip'] }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.referer_host') }}</label>
					<input type="text" name="referer_host" class="form-control" value="{{ $filters['referer_host'] }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">{{ __('website_visit_logs.min_time_spent') }}</label>
					<input type="number" min="0" name="min_time_spent" class="form-control" value="{{ $filters['min_time_spent'] }}">
				</div>
				<div class="col-12 d-flex gap-2 mt-2">
					<button type="submit" class="btn btn-primary">{{ __('website_visit_logs.apply_filters') }}</button>
					<a href="{{ route('website-visit-logs.index') }}" class="btn btn-light">{{ __('website_visit_logs.reset_filters') }}</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="visit-logs-table" class="table table-hover w-100">
					<thead>
						<tr>
							<th><input type="checkbox" id="select-all-visits"></th>
							<th>{{ __('website_visit_logs.date_time') }}</th>
							<th>{{ __('website_visit_logs.type') }}</th>
							<th>{{ __('website_visit_logs.ip') }}</th>
							<th>{{ __('website_visit_logs.page') }}</th>
							<th>{{ __('website_visit_logs.entity') }}</th>
							<th>{{ __('website_visit_logs.time_spent') }}</th>
							<th>{{ __('website_visit_logs.events') }}</th>
							<th>{{ __('website_visit_logs.referer') }}</th>
							<th>{{ __('website_visit_logs.locale') }}</th>
							<th>{{ __('website_visit_logs.actions') }}</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="visitLogModal" tabindex="-1" aria-labelledby="visitLogModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="visitLogModalLabel">{{ __('website_visit_logs.details') }}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('website_visit_logs.cancel') }}"></button>
			</div>
			<div class="modal-body">
				<div id="visit-log-modal-loading" class="text-center py-4 text-muted d-none">
					<div class="spinner-border spinner-border-sm me-2" role="status"></div>
					{{ __('website_visit_logs.loading') }}
				</div>
				<div id="visit-log-modal-content" class="d-none">
					<div class="row g-3">
						<div class="col-lg-6">
							<table class="table table-borderless table-sm mb-0">
								<tr>
									<th class="w-35">{{ __('website_visit_logs.date_time') }}</th>
									<td data-field="visited_at">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.type') }}</th>
									<td data-field="visitor_label">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.ip') }}</th>
									<td data-field="ip">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.locale') }}</th>
									<td data-field="locale">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.page') }}</th>
									<td>
										<div data-field="page_path">—</div>
										<small class="text-muted" data-field="page_type_label"></small>
									</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.page_url') }}</th>
									<td class="text-break" data-field="page_url">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.page_title') }}</th>
									<td data-field="page_title">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.entity') }}</th>
									<td data-field="entity">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.time_spent') }}</th>
									<td data-field="time_spent">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.referer') }}</th>
									<td class="text-break" data-field="referer">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.referer_host') }}</th>
									<td data-field="referer_host">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.user_agent') }}</th>
									<td class="text-break small" data-field="user_agent">—</td>
								</tr>
								<tr>
									<th>{{ __('website_visit_logs.session_id') }}</th>
									<td class="small" data-field="session_id">—</td>
								</tr>
								<!-- <tr>
									<th>{{ __('website_visit_logs.visitor_token') }}</th>
									<td class="small" data-field="visitor_token">—</td>
								</tr> -->
							</table>
						</div>
						<div class="col-lg-6">
							<h6 class="mb-3">
								{{ __('website_visit_logs.event_timeline') }}
								<span class="badge bg-secondary" data-field="events_count">0</span>
							</h6>
							<div id="visit-log-events-empty" class="text-muted d-none">{{ __('website_visit_logs.no_events') }}</div>
							<div id="visit-log-events-wrap" class="visit-event-timeline d-none"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('website_visit_logs.close') }}</button>
				<button type="button" class="btn btn-danger" id="visit-log-modal-delete">
					<i class="ti ti-trash"></i> {{ __('website_visit_logs.delete') }}
				</button>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ URL::asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(document).ready(function () {
	function escapeHtml(value) {
		if (value === null || value === undefined) return '';
		return String(value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function filterParams() {
		var params = {};
		$.each($('#visit-filters').serializeArray(), function (_, field) {
			if (field.name.endsWith('[]')) {
				var key = field.name.slice(0, -2);
				if (!params[key]) params[key] = [];
				params[key].push(field.value);
			} else {
				params[field.name] = field.value;
			}
		});
		return params;
	}

	function renderStatList(selector, rows) {
		var $el = $(selector);
		$el.empty();
		if (!rows || !rows.length) {
			$el.append('<li class="list-group-item px-0 text-muted">{{ __('website_visit_logs.no_data') }}</li>');
			return;
		}
		rows.forEach(function (row) {
			$el.append(
				'<li class="list-group-item d-flex justify-content-between px-0">' +
					'<span class="text-truncate me-2">' + escapeHtml(row.label) + '</span>' +
					'<span class="badge bg-secondary">' + escapeHtml(row.total) + '</span>' +
				'</li>'
			);
		});
	}

	var chart = null;
	function renderChart(daily) {
		var ctx = document.getElementById('visitsChart');
		if (!ctx) return;
		if (chart) chart.destroy();
		chart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: (daily && daily.labels) || [],
				datasets: [{
					label: '{{ __('website_visit_logs.stats_visits') }}',
					data: (daily && daily.values) || [],
					borderColor: '#3E66F3',
					backgroundColor: 'rgba(62,102,243,0.15)',
					fill: true,
					tension: 0.25
				}]
			},
			options: {
				responsive: true,
				plugins: { legend: { display: false } },
				scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
			}
		});
	}

	function refreshStats() {
		$.getJSON("{{ route('website-visit-logs.stats') }}", filterParams(), function (stats) {
			$('[data-stat="visits"]').text(stats.totals.visits);
			$('[data-stat="unique_ips"]').text(stats.totals.unique_ips);
			$('[data-stat="humans"]').text(stats.totals.humans);
			$('[data-stat="bots"]').text(stats.totals.bots);
			$('[data-stat="avg_time_spent"]').text(stats.totals.avg_time_spent + '{{ __('website_visit_logs.seconds') }}');
			renderStatList('#stat-pages', stats.pages);
			renderStatList('#stat-doctors', stats.doctors);
			renderStatList('#stat-clinics', stats.clinics);
			renderStatList('#stat-blog_posts', stats.blog_posts);
			renderStatList('#stat-referrers', stats.referrers);
			renderStatList('#stat-links', stats.links);
			renderChart(stats.daily);
		});
	}

	var table = $('#visit-logs-table').DataTable({
		processing: true,
		serverSide: true,
		autoWidth: false,
		scrollX: true,
		pageLength: 25,
		ajax: {
			url: "{{ route('website-visit-logs.data') }}",
			type: 'GET',
			data: function (d) {
				return $.extend({}, d, filterParams());
			}
		},
		columns: [
			{
				data: 'id',
				orderable: false,
				searchable: false,
				render: function (data) {
					return '<input type="checkbox" class="visit-row-check" value="' + data + '">';
				}
			},
			{ data: 'visited_at', name: 'visited_at' },
			{
				data: 'visitor_label',
				name: 'is_bot',
				render: function (data, type, row) {
					var cls = row.is_bot ? 'bg-warning text-dark' : 'bg-success';
					return '<span class="badge ' + cls + '">' + escapeHtml(data) + '</span>';
				}
			},
			{ data: 'ip', name: 'ip', defaultContent: '-' },
			{
				data: 'page_path',
				name: 'page_path',
				render: function (data, type, row) {
					return '<div>' + escapeHtml(data) + '</div><small class="text-muted">' + escapeHtml(row.page_type_label) + '</small>';
				}
			},
			{ data: 'entity', name: 'entity', orderable: false },
			{ data: 'time_spent', name: 'time_spent_seconds' },
			{ data: 'events_count', name: 'events_count' },
			{ data: 'referer_host', name: 'referer_host' },
			{ data: 'locale', name: 'locale' },
			{
				data: 'id',
				orderable: false,
				searchable: false,
				render: function (data) {
					return '' +
						'<button type="button" class="btn btn-sm btn-info me-1 show-btn" data-id="' + data + '" title="{{ __('website_visit_logs.view') }}"><i class="ti ti-eye"></i></button>' +
						'<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' + data + '"><i class="ti ti-trash"></i></button>';
				}
			}
		],
		order: [[1, 'desc']],
		language: (typeof languages !== 'undefined' && typeof language !== 'undefined' && languages[language])
			? languages[language]
			: (typeof languages !== 'undefined' ? languages['en'] : undefined)
	});

	var currentModalVisitId = null;
	var visitModalEl = document.getElementById('visitLogModal');
	var visitModal = (typeof bootstrap !== 'undefined' && visitModalEl)
		? new bootstrap.Modal(visitModalEl)
		: null;

	function setModalField(field, value, asHtml) {
		var $el = $('#visit-log-modal-content [data-field="' + field + '"]');
		if (!$el.length) return;
		if (asHtml) {
			$el.html(value);
		} else {
			$el.text(value == null || value === '' ? '—' : value);
		}
	}

	function fillVisitModal(data) {
		currentModalVisitId = data.id;
		$('#visitLogModalLabel').text('{{ __('website_visit_logs.details') }} #' + data.id);

		var typeBadge = data.is_bot
			? '<span class="badge bg-warning text-dark">' + escapeHtml(data.visitor_label) + '</span>'
			: '<span class="badge bg-success">' + escapeHtml(data.visitor_label) + '</span>';

		setModalField('visited_at', data.visited_at);
		setModalField('visitor_label', typeBadge, true);
		setModalField('ip', data.ip);
		setModalField('locale', data.locale);
		setModalField('page_path', data.page_path);
		setModalField('page_type_label', data.page_type_label);
		setModalField('page_url', data.page_url);
		setModalField('page_title', data.page_title);
		setModalField('entity', data.entity);
		setModalField('time_spent', data.time_spent);
		setModalField('referer', data.referer);
		setModalField('referer_host', data.referer_host);
		setModalField('user_agent', data.user_agent);
		setModalField('session_id', data.session_id);
		setModalField('visitor_token', data.visitor_token);
		setModalField('events_count', data.events_count);

		var $timeline = $('#visit-log-events-wrap').empty();
		var events = data.events || [];
		if (!events.length) {
			$('#visit-log-events-empty').removeClass('d-none');
			$timeline.addClass('d-none');
		} else {
			$('#visit-log-events-empty').addClass('d-none');
			$timeline.removeClass('d-none');
			events.forEach(function (event) {
				var tone = event.tone || 'primary';
				var icon = event.icon || 'ti-activity';
				var detailsHtml = '';
				if (event.details && event.details.length) {
					detailsHtml = '<ul class="visit-event-details mb-0 mt-1">';
					event.details.forEach(function (detail) {
						var valueHtml = detail.is_url
							? '<a href="' + escapeHtml(detail.value) + '" target="_blank" rel="noopener noreferrer" class="text-break">' + escapeHtml(detail.value) + '</a>'
							: '<span class="text-break">' + escapeHtml(detail.value) + '</span>';
						detailsHtml += '<li><span class="text-muted">' + escapeHtml(detail.label) + ':</span> ' + valueHtml + '</li>';
					});
					detailsHtml += '</ul>';
				}
				$timeline.append(
					'<div class="visit-event-item visit-event-item--' + escapeHtml(tone) + '">' +
						'<div class="visit-event-icon"><i class="ti ' + escapeHtml(icon) + '"></i></div>' +
						'<div class="visit-event-body">' +
							'<div class="d-flex justify-content-between gap-2 flex-wrap">' +
								'<strong>' + escapeHtml(event.label || event.name || '—') + '</strong>' +
								'<small class="text-muted">' + escapeHtml(event.at_label || event.at || '—') + '</small>' +
							'</div>' +
							(event.summary ? '<div class="text-muted small mt-1">' + escapeHtml(event.summary) + '</div>' : '') +
							detailsHtml +
						'</div>' +
					'</div>'
				);
			});
		}
	}

	function openVisitModal(id) {
		currentModalVisitId = id;
		$('#visit-log-modal-loading').removeClass('d-none');
		$('#visit-log-modal-content').addClass('d-none');
		if (visitModal) {
			visitModal.show();
		} else {
			$('#visitLogModal').modal('show');
		}

		$.ajax({
			url: "{{ url('admin/website-visit-logs') }}/" + id,
			type: 'GET',
			data: { modal: 1 },
			headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
			success: function (data) {
				fillVisitModal(data);
				$('#visit-log-modal-loading').addClass('d-none');
				$('#visit-log-modal-content').removeClass('d-none');
			},
			error: function () {
				if (visitModal) visitModal.hide();
				else $('#visitLogModal').modal('hide');
				toastr.error('{{ __('website_visit_logs.loading') }}');
			}
		});
	}

	function deleteVisit(id, afterDelete) {
		Swal.fire({
			title: '{{ __('website_visit_logs.are_you_sure') }}',
			text: '{{ __('website_visit_logs.delete_confirm') }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __('website_visit_logs.yes_delete') }}',
			cancelButtonText: '{{ __('website_visit_logs.cancel') }}'
		}).then(function (result) {
			if (!result.isConfirmed) return;
			$.ajax({
				url: "{{ url('admin/website-visit-logs') }}/" + id,
				type: 'DELETE',
				headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
				success: function (res) {
					table.ajax.reload(null, false);
					refreshStats();
					toastr.success(res.message || '{{ __('website_visit_logs.deleted') }}');
					if (typeof afterDelete === 'function') afterDelete();
				}
			});
		});
	}

	function selectedIds() {
		return $('.visit-row-check:checked').map(function () { return $(this).val(); }).get();
	}

	function syncBulkButton() {
		$('#bulk-delete-btn').prop('disabled', selectedIds().length === 0);
	}

	$('#select-all-visits').on('change', function () {
		$('.visit-row-check').prop('checked', this.checked);
		syncBulkButton();
	});

	$(document).on('change', '.visit-row-check', syncBulkButton);

	$('#visit-filters').on('submit', function (e) {
		e.preventDefault();
		table.ajax.reload();
		refreshStats();
		var qs = $.param(filterParams());
		history.replaceState(null, '', "{{ route('website-visit-logs.index') }}" + (qs ? ('?' + qs) : ''));
	});

	$(document).on('click', '.show-btn', function () {
		openVisitModal($(this).data('id'));
	});

	$('#visit-log-modal-delete').on('click', function () {
		if (!currentModalVisitId) return;
		deleteVisit(currentModalVisitId, function () {
			if (visitModal) visitModal.hide();
			else $('#visitLogModal').modal('hide');
		});
	});

	$(document).on('click', '.delete-btn', function () {
		deleteVisit($(this).data('id'));
	});

	$('#bulk-delete-btn').on('click', function () {
		var ids = selectedIds();
		if (!ids.length) {
			toastr.warning('{{ __('website_visit_logs.select_rows') }}');
			return;
		}
		Swal.fire({
			title: '{{ __('website_visit_logs.are_you_sure') }}',
			text: '{{ __('website_visit_logs.bulk_delete_confirm') }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __('website_visit_logs.yes_delete') }}',
			cancelButtonText: '{{ __('website_visit_logs.cancel') }}'
		}).then(function (result) {
			if (!result.isConfirmed) return;
			$.ajax({
				url: "{{ route('website-visit-logs.bulk-delete') }}",
				type: 'POST',
				headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
				data: { ids: ids },
				success: function (res) {
					$('#select-all-visits').prop('checked', false);
					syncBulkButton();
					table.ajax.reload(null, false);
					refreshStats();
					toastr.success(res.message);
				}
			});
		});
	});

	renderChart(@json($stats['daily']));
});
</script>
@endpush
