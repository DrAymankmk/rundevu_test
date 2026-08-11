@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:20px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box"
				style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
				<h4 class="page-title">{{ __('cms.links') }}</h4>
				<div class="page-title-right">
					<button type="button" class="btn btn-primary" data-bs-toggle="modal"
						data-bs-target="#linkModal">
						<i class="mdi mdi-plus"></i> {{ __('cms.add_link') }}
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- DataTable Card -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<table id="links-table" class="table dt-responsive nowrap w-100">
						<thead>
							<tr>
								<th>{{ __('cms.id') }}</th>
								<th>{{ __('cms.name') }}</th>
								<th>{{ __('cms.link') }}</th>
								<th>{{ __('cms.attached_to') }}</th>
								<th>{{ __('cms.type') }}</th>
								<th>{{ __('cms.target') }}</th>
								<th>{{ __('cms.status') }}</th>
								<th>{{ __('cms.order') }}</th>
								<th>{{ __('cms.actions') }}</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Link Modal -->
<div class="modal fade" id="linkModal" tabindex="-1" aria-labelledby="linkModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="linkModalLabel">{{ __('cms.add_link') }}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"
					aria-label="Close"></button>
			</div>
			<form id="linkForm">
				@csrf
				<input type="hidden" id="link_id" name="id">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="linkable_type"
								class="form-label">{{ __('cms.attach_to') }}
								<span
									class="text-danger">*</span></label>
							<select class="form-select" id="linkable_type"
								name="linkable_type" required>
								<option value="page">
									{{ __('cms.page') }}
								</option>
								<option value="section">
									{{ __('cms.section') }}
								</option>
								<option value="item">
									{{ __('cms.item') }}
								</option>
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label for="linkable_id"
								class="form-label">{{ __('cms.select') }}
								<span
									class="text-danger">*</span></label>
							<select class="form-select" id="linkable_id"
								name="linkable_id" required>
								<!-- Populated dynamically -->
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="name"
								class="form-label">{{ __('cms.name') }}
								<span
									class="text-danger">*</span></label>
							<input type="text" class="form-control" id="name"
								name="name" required>
							<div class="invalid-feedback" id="name-error">
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<label for="link"
								class="form-label">{{ __('cms.url') }}</label>
							<input type="text" class="form-control" id="link"
								name="link"
								placeholder="https://example.com">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 mb-3">
							<label for="link_icon"
								class="form-label">{{ __('cms.icon') }}</label>
							@include('components.icon-picker', [
							'inputId' => 'link_icon',
							'inputName' => 'icon',
							'value' => ''
							])
						</div>
						<div class="col-md-4 mb-3">
							<label for="target"
								class="form-label">{{ __('cms.target') }}
								<span
									class="text-danger">*</span></label>
							<select class="form-select" id="target"
								name="target" required>
								<option value="_self">
									{{ __('cms.same_window') }}
								</option>
								<option value="_blank">
									{{ __('cms.new_window') }}
								</option>
							</select>
						</div>
						<div class="col-md-4 mb-3">
							<label for="type"
								class="form-label">{{ __('cms.type') }}</label>
							<input type="text" class="form-control" id="type"
								name="type"
								placeholder="primary, social, etc.">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="order"
								class="form-label">{{ __('cms.order') }}</label>
							<input type="number" class="form-control"
								id="order" name="order" value="0">
						</div>
						<div class="col-md-6 mb-3">
							<div class="form-check form-switch mt-4">
								<input class="form-check-input"
									type="checkbox" id="is_active"
									name="is_active" checked>
								<label class="form-check-label"
									for="is_active">{{ __('cms.active') }}</label>
							</div>
						</div>
					</div>

					<!-- Translation Tabs -->
					<hr>
					<h6>{{ __('cms.translations') }}</h6>
					<ul class="nav nav-tabs" id="translationTabs" role="tablist">
						@foreach($languages as $index => $lang)
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $index === 0 ? 'active' : '' }}"
								id="trans-tab-{{ $lang->code }}"
								data-bs-toggle="tab"
								data-bs-target="#trans-content-{{ $lang->code }}"
								type="button" role="tab">
								{{ $lang->flag ?? '' }}
								{{ $lang->name }}
							</button>
						</li>
						@endforeach
					</ul>
					<div class="tab-content pt-3" id="translationTabsContent">
						@foreach($languages as $index => $lang)
						<div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
							id="trans-content-{{ $lang->code }}"
							role="tabpanel">
							<div class="mb-3">
								<label class="form-label">{{ __('cms.translated_name') }}
									({{ $lang->name }})</label>
								<input type="text"
									class="form-control translation-name"
									name="translations[{{ $lang->code }}][name]"
									data-locale="{{ $lang->code }}"
									dir="{{ $lang->direction }}">
							</div>
						</div>
						@endforeach
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-bs-dismiss="modal">{{ __('cms.cancel') }}</button>
					<button type="submit" class="btn btn-primary"
						id="saveBtn">{{ __('cms.save') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
	// Data for dropdowns
	var pages = @json($pages);
	var sections = @json($sections);
	var items = @json($items);

	// Populate linkable dropdown based on type
	function populateLinkableDropdown(type, selectedId) {
		var $select = $('#linkable_id');
		$select.empty();

		var data = [];
		if (type === 'page') {
			data = pages;
		} else if (type === 'section') {
			data = sections;
		} else if (type === 'item') {
			data = items;
		}

		data.forEach(function(item) {
			var label = item.name;
			if (type === 'section' && item.page) {
				label = item.page.name + ' - ' + item
					.name;
			} else if (type === 'item' && item.section) {
				label = (item.section.page ? item
						.section.page.name +
						' - ' : '') + item
					.section.name + ' - ' + (item
						.translations &&
						item.translations[
							0] ? item
						.translations[0]
						.title : 'Item ' +
						item.id);
			}

			var selected = item.id == selectedId ?
				'selected' : '';
			$select.append('<option value="' + item.id +
				'" ' + selected + '>' +
				label + '</option>');
		});
	}

	// Initial population
	populateLinkableDropdown('page');

	// On linkable type change
	$('#linkable_type').on('change', function() {
		populateLinkableDropdown($(this).val());
	});

	// Initialize DataTable
	var table = $('#links-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "{{ route('cms.links.data') }}",
			type: 'GET'
		},
		columns: [{
				data: 'id',
				name: 'id'
			},
			{
				data: 'translated_name',
				name: 'name'
			},
			{
				data: 'link',
				name: 'link',
				render: function(data) {
					return data ?
						'<a href="' +
						data +
						'" target="_blank">' +
						(data.length >
							30 ?
							data
							.substring(
								0,
								30
							) +
							'...' :
							data
						) +
						'</a>' :
						'-';
				}
			},
			{
				data: null,
				render: function(data) {
					return '<span class="badge bg-secondary">' +
						data
						.linkable_type +
						'</span> ' +
						data
						.linkable_name;
				}
			},
			{
				data: 'type',
				name: 'type'
			},
			{
				data: 'target',
				name: 'target',
				render: function(data) {
					return data ===
						'_blank' ?
						'<i class="mdi mdi-open-in-new"></i>' :
						'<i class="mdi mdi-window-maximize"></i>';
				}
			},
			{
				data: 'is_active',
				name: 'is_active',
				render: function(data, type,
					row) {
					var checked =
						data ?
						'checked' :
						'';
					return '<div class="form-check form-switch"><input class="form-check-input toggle-status" type="checkbox" data-id="' +
						row
						.id +
						'" ' +
						checked +
						'></div>';
				}
			},
			{
				data: 'order',
				name: 'order'
			},
			{
				data: null,
				orderable: false,
				render: function(data, type,
					row) {
					return `
                        <button class="btn btn-sm btn-info edit-btn" data-id="${row.id}">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    `;
				}
			}
		],
		order: [
			[7, 'asc']
		],
		language: languages[language] || languages['en']
	});

	// Reset form when modal is closed
	$('#linkModal').on('hidden.bs.modal', function() {
		$('#linkForm')[0].reset();
		$('#link_id').val('');
		$('#linkModalLabel').text('{{ __("cms.add_link") }}');
		$('.is-invalid').removeClass('is-invalid');
		$('#is_active').prop('checked', true);
		populateLinkableDropdown('page');
	});

	// Form Submit
	$('#linkForm').on('submit', function(e) {
		e.preventDefault();

		var id = $('#link_id').val();
		var url = id ? "{{ url('admin/cms/links') }}/" + id :
			"{{ route('cms.links.store') }}";
		var method = id ? 'PUT' : 'POST';

		var formData = {
			_token: '{{ csrf_token() }}',
			linkable_type: $('#linkable_type')
				.val(),
			linkable_id: $('#linkable_id').val(),
			name: $('#name').val(),
			link: $('#link').val(),
			icon: $('#icon').val(),
			target: $('#target').val(),
			type: $('#type').val(),
			order: $('#order').val() || 0,
			is_active: $('#is_active').is(
				':checked') ? 1 : 0,
			translations: {}
		};

		// Collect translations
		$('.translation-name').each(function() {
			var locale = $(this).data(
				'locale');
			formData.translations[
				locale
			] = {
				name: $(this)
					.val()
			};
		});

		$.ajax({
			url: url,
			type: method,
			data: formData,
			success: function(response) {
				if (response
					.success
				) {
					$('#linkModal')
						.modal(
							'hide'
						);
					table.ajax
						.reload();
					Swal.fire({
						icon: 'success',
						title: '{{ __("cms.success") }}',
						text: response
							.message,
						timer: 2000,
						showConfirmButton: false
					});
				}
			},
			error: function(xhr) {
				if (xhr.status ===
					422
				) {
					var errors =
						xhr
						.responseJSON
						.errors;
					$.each(errors, function(key,
						value
					) {
						$('#' + key)
							.addClass(
								'is-invalid'
							);
						$('#' + key +
								'-error'
							)
							.text(value[
								0
							]);
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: '{{ __("cms.error") }}',
						text: xhr.responseJSON
							.message ||
							'{{ __("cms.an_error_occurred") }}'
					});
				}
			}
		});
	});

	// Edit Button Click
	$(document).on('click', '.edit-btn', function() {
		var id = $(this).data('id');

		$.get("{{ url('admin/cms/links') }}/" + id, function(
			response) {
			if (response.success) {
				var data = response
					.data;
				$('#link_id').val(data
					.id
				);
				$('#linkable_type')
					.val(data
						.linkable_type_short
					);
				populateLinkableDropdown
					(data.linkable_type_short,
						data
						.linkable_id
					);
				$('#name').val(data
					.name
				);
				$('#link').val(data
					.link
				);
				$('#icon').val(data
					.icon
				);
				$('#target').val(data
					.target
				);
				$('#type').val(data
					.type
				);
				$('#order').val(data
					.order
				);
				$('#is_active')
					.prop('checked',
						data
						.is_active
					);

				// Set translations
				if (data
					.translations_keyed
				) {
					$.each(data.translations_keyed,
						function(locale,
							translation
						) {
							$('input[name="translations[' +
									locale +
									'][name]"]'
								)
								.val(translation
									.name
								);
						}
					);
				}

				$('#linkModalLabel')
					.text(
						'{{ __("cms.edit_link") }}'
					);
				$('#linkModal')
					.modal(
						'show'
					);
			}
		});
	});

	// Delete Button Click
	$(document).on('click', '.delete-btn', function() {
		var id = $(this).data('id');

		Swal.fire({
			title: '{{ __("cms.are_you_sure") }}',
			text: '{{ __("cms.this_action_cannot_be_undone") }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __("cms.yes_delete_it") }}',
			cancelButtonText: '{{ __("cms.cancel") }}'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "{{ url('admin/cms/links') }}/" +
						id,
					type: 'DELETE',
					data: {
						_token: '{{ csrf_token() }}'
					},
					success: function(
						response
					) {
						if (response
							.success
						) {
							table.ajax
								.reload();
							Swal.fire({
								icon: 'success',
								title: '{{ __("cms.deleted") }}',
								text: response
									.message,
								timer: 2000,
								showConfirmButton: false
							});
						}
					},
					error: function(
						xhr
					) {
						Swal.fire({
							icon: 'error',
							title: '{{ __("cms.error") }}',
							text: xhr.responseJSON
								.message ||
								'{{ __("cms.an_error_occurred") }}'
						});
					}
				});
			}
		});
	});

	// Toggle Status
	$(document).on('change', '.toggle-status', function() {
		var id = $(this).data('id');

		$.ajax({
			url: "{{ url('admin/cms/links') }}/" +
				id +
				"/toggle-status",
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}'
			},
			success: function(response) {
				if (response
					.success
				) {
					Swal.fire({
						icon: 'success',
						title: '{{ __("cms.success") }}',
						text: response
							.message,
						timer: 1500,
						showConfirmButton: false
					});
				}
			},
			error: function(xhr) {
				table.ajax
					.reload();
				Swal.fire({
					icon: 'error',
					title: '{{ __("cms.error") }}',
					text: xhr.responseJSON
						.message ||
						'{{ __("cms.an_error_occurred") }}'
				});
			}
		});
	});
});
</script>
@endpush