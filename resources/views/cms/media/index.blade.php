@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:20px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<button type="button" class="btn btn-primary" data-bs-toggle="modal"
						data-bs-target="#uploadMediaModal">
						<i class="mdi mdi-upload"></i> {{ __('cms.upload_media') }}
					</button>
					<button type="button" class="btn btn-danger" id="bulkDeleteBtn"
						style="display: none;">
						<i class="mdi mdi-delete"></i>
						{{ __('cms.delete_selected') }}
					</button>
				</div>
				<h4 class="page-title">{{ __('cms.media_library') }}</h4>
			</div>
		</div>
	</div>

	@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif

	<!-- Filters -->
	<div class="row mb-3">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row g-3">
						<div class="col-md-3">
							<label
								class="form-label">{{ __('cms.collection') }}</label>
							<select class="form-select" id="filterCollection">
								<option value="">
									{{ __('cms.all_collections') }}
								</option>
							</select>
						</div>
						<div class="col-md-3">
							<label
								class="form-label">{{ __('cms.type') }}</label>
							<select class="form-select" id="filterType">
								<option value="">
									{{ __('cms.all_types') }}
								</option>
								<option value="image">
									{{ __('cms.images') }}
								</option>
								<option value="video">
									{{ __('cms.videos') }}
								</option>
								<option value="audio">
									{{ __('cms.audio') }}
								</option>
								<option value="application">
									{{ __('cms.documents') }}
								</option>
							</select>
						</div>
						<div class="col-md-6">
							<label
								class="form-label">{{ __('cms.search') }}</label>
							<input type="text" class="form-control"
								id="searchInput"
								placeholder="{{ __('cms.search_by_name_filename_or_type') }}">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- DataTable Card -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table id="media-table"
							class="table dt-responsive nowrap w-100">
							<thead>
								<tr>
									<th width="30">
										<input type="checkbox"
											id="selectAll"
											class="form-check-input">
									</th>
									<th width="80">
										{{ __('cms.preview') }}
									</th>
									<th width="80">
										{{ __('cms.name') }}
									</th>
									<th width="80">
										{{ __('cms.file_name') }}
									</th>
									<th>{{ __('cms.type') }}</th>
									<th>{{ __('cms.size') }}</th>
									<th>{{ __('cms.collection') }}
									</th>
									<th>{{ __('cms.uploaded') }}
									</th>
									<th>{{ __('cms.actions') }}
									</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Upload Media Modal -->
<div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-labelledby="uploadMediaModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="uploadMediaForm" enctype="multipart/form-data">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="uploadMediaModalLabel">
						{{ __('cms.upload_media') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"
						aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label">{{ __('cms.files') }} <span
								class="text-danger">*</span></label>
						<input type="file" class="form-control" name="files[]"
							id="mediaFiles" multiple required>
						<small
							class="text-muted">{{ __('cms.you_can_select_multiple_files_max_size_10mb_per_file') }}</small>
					</div>
					<div class="mb-3">
						<label class="form-label">{{ __('cms.collection') }}</label>
						<input type="text" class="form-control" name="collection"
							placeholder="{{ __('cms.default') }}">
						<small
							class="text-muted">{{ __('cms.optional_assign_to_a_collection') }}</small>
					</div>
					<div id="uploadPreview" class="row g-2"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-bs-dismiss="modal">{{ __('cms.cancel') }}</button>
					<button type="submit" class="btn btn-primary">
						<i class="mdi mdi-upload"></i> {{ __('cms.upload') }}
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Media Modal -->
<div class="modal fade" id="editMediaModal" tabindex="-1" aria-labelledby="editMediaModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="editMediaForm" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<div class="modal-header">
					<h5 class="modal-title" id="editMediaModalLabel">
						{{ __('cms.edit_media') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"
						aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<!-- Current Media Preview -->
					<div class="mb-3">
						<label
							class="form-label">{{ __('cms.current_media') }}</label>
						<div id="currentMediaPreview"
							class="text-center p-3 border rounded">
							<!-- Preview will be inserted here -->
						</div>
					</div>

					<!-- Replace Media File -->
					<div class="mb-3">
						<label
							class="form-label">{{ __('cms.replace_media_file') }}</label>
						<input type="file" class="form-control" name="file"
							id="editMediaFile" accept="*/*">
						<small
							class="text-muted">{{ __('cms.leave_empty_to_keep_current_file_max_size_10mb') }}</small>
						<div id="newFilePreview" class="mt-2"
							style="display: none;">
							<label
								class="form-label">{{ __('cms.new_file_preview') }}</label>
							<div class="text-center p-2 border rounded">
								<!-- New file preview will be inserted here -->
							</div>
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label">{{ __('cms.name') }}</label>
						<input type="text" class="form-control" name="name"
							id="editMediaName">
					</div>
					<div class="mb-3">
						<label class="form-label">{{ __('cms.collection') }}</label>
						<input type="text" class="form-control"
							name="collection_name" id="editMediaCollection">
					</div>
					<input type="hidden" name="media_id" id="editMediaId">
					<input type="hidden" name="model_type" id="editMediaModelType">
					<input type="hidden" name="model_id" id="editMediaModelId">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary"
						data-bs-dismiss="modal">{{ __('cms.cancel') }}</button>
					<button type="submit"
						class="btn btn-primary">{{ __('cms.update') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
	let table = $('#media-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: '{{ route("cms.media.data") }}',
			data: function(d) {
				d.collection = $(
						'#filterCollection'
					)
					.val();
				d.type = $('#filterType')
					.val();
			}
		},
		columns: [{
				data: 'id',
				orderable: false,
				searchable: false,
				render: function(data, type,
					row) {
					return '<input type="checkbox" class="form-check-input media-checkbox" value="' +
						data +
						'">';
				}
			},
			{
				data: 'thumbnail',
				orderable: false,
				searchable: false,
				render: function(data, type,
					row) {
					if (row
						.is_image
					) {
						return '<img src="' +
							data +
							'" alt="' +
							row
							.name +
							'" class="img-thumbnail" style="max-width: 60px; max-height: 60px; object-fit: cover;">';
					}
					return '<i class="mdi mdi-file" style="font-size: 40px;"></i>';
				}
			},
			{
				data: 'name'
			},
			{
				data: 'file_name'
			},
			{
				data: 'mime_type'
			},
			{
				data: 'size'
			},
			{
				data: 'collection_name'
			},
			{
				data: 'created_at'
			},
			{
				data: 'id',
				orderable: false,
				searchable: false,
				render: function(data, type,
					row) {
					return `
                        <div class="btn-group">
                            <a href="${row.url}" target="_blank" class="btn btn-sm btn-info" title="{{ __('cms.view') }}">
                                <i class="mdi mdi-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-primary edit-media"
                                    data-id="${data}"
                                    data-name="${row.name}"
                                    data-collection="${row.collection_name}"
                                    data-thumbnail="${row.thumbnail}"
                                    data-is-image="${row.is_image}"
                                    data-file-name="${row.file_name}"
                                    data-model-type="${row.model_type}"
                                    data-model-id="${row.model_id}"
                                    title="{{ __('cms.edit') }}">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-media" data-id="${data}" title="{{ __('cms.delete') }}">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    `;
				}
			}
		],
		order: [
			[8, 'desc']
		],
		language: languages[language]
	});

	// Load collections
	$.get('{{ route("cms.media.collections") }}', function(collections) {
		collections.forEach(function(collection) {
			$('#filterCollection').append(
				'<option value="' +
				collection +
				'">' +
				collection +
				'</option>'
			);
		});
	});

	// Filters
	$('#filterCollection, #filterType').on('change', function() {
		table.ajax.reload();
	});

	$('#searchInput').on('keyup', function() {
		table.search(this.value).draw();
	});

	// Select All
	$('#selectAll').on('change', function() {
		$('.media-checkbox').prop('checked', this.checked);
		toggleBulkDeleteBtn();
	});

	$(document).on('change', '.media-checkbox', function() {
		toggleBulkDeleteBtn();
	});

	function toggleBulkDeleteBtn() {
		const checked = $('.media-checkbox:checked').length;
		$('#bulkDeleteBtn').toggle(checked > 0);
	}

	// Upload Media
	$('#uploadMediaForm').on('submit', function(e) {
		e.preventDefault();
		const formData = new FormData();
		const files = $('#mediaFiles')[0].files;
		const collection = $('input[name="collection"]').val() ||
			'default';

		for (let i = 0; i < files.length; i++) {
			formData.append('files[]', files[i]);
		}
		formData.append('collection', collection);
		formData.append('_token', '{{ csrf_token() }}');

		$.ajax({
			url: '{{ route("cms.media.store") }}',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				if (response
					.success
				) {
					$('#uploadMediaModal')
						.modal(
							'hide'
						);
					$('#uploadMediaForm')[
							0
						]
						.reset();
					$('#uploadPreview')
						.empty();
					table.ajax
						.reload();
					Swal.fire({
						icon: 'success',
						title: '{{ __("cms.success") }}',
						text: response
							.message
					});
				}
			},
			error: function(xhr) {
				Swal.fire({
					icon: 'error',
					title: '{{ __("cms.error") }}',
					text: xhr.responseJSON
						?.message ||
						'{{ __("cms.an_error_occurred") }}'
				});
			}
		});
	});

	// Preview files before upload
	$('#mediaFiles').on('change', function() {
		const files = this.files;
		$('#uploadPreview').empty();

		Array.from(files).forEach(function(file) {
			const reader =
				new FileReader();
			reader.onload = function(e) {
				if (file.type
					.startsWith(
						'image/'
					)
				) {
					$('#uploadPreview')
						.append(`
                        <div class="col-md-3">
                            <img src="${e.target.result}" class="img-thumbnail" style="max-width: 100%; height: 100px; object-fit: cover;">
                            <small class="d-block">${file.name}</small>
                        </div>
                    `);
				} else {
					$('#uploadPreview')
						.append(`
                        <div class="col-md-3">
                            <div class="border p-2 text-center">
                                <i class="mdi mdi-file" style="font-size: 40px;"></i>
                                <small class="d-block">${file.name}</small>
                            </div>
                        </div>
                    `);
				}
			};
			reader.readAsDataURL(file);
		});
	});

	// Edit Media
	$(document).on('click', '.edit-media', function() {
		const id = $(this).data('id');
		const name = $(this).data('name');
		const collection = $(this).data('collection');
		const thumbnail = $(this).data('thumbnail');
		const isImage = $(this).data('is-image') === true || $(this)
			.data('is-image') === 'true';
		const fileName = $(this).data('file-name');
		const modelType = $(this).data('model-type');
		const modelId = $(this).data('model-id');

		$('#editMediaId').val(id);
		$('#editMediaName').val(name);
		$('#editMediaCollection').val(collection);
		$('#editMediaModelType').val(modelType);
		$('#editMediaModelId').val(modelId);

		// Show current media preview
		let previewHtml = '';
		if (isImage) {
			previewHtml =
				`<img src="${thumbnail}" alt="${name}" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;"><br><small class="text-muted">${fileName}</small>`;
		} else {
			previewHtml =
				`<i class="mdi mdi-file" style="font-size: 60px;"></i><br><small class="text-muted">${fileName}</small>`;
		}
		$('#currentMediaPreview').html(previewHtml);

		// Reset file input and preview
		$('#editMediaFile').val('');
		$('#newFilePreview').hide().find('div').empty();

		$('#editMediaModal').modal('show');
	});

	// Preview new file when selected
	$('#editMediaFile').on('change', function() {
		const file = this.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = function(e) {
				let previewHtml = '';
				if (file.type.startsWith(
						'image/'
					)) {
					previewHtml =
						`<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;"><br><small>${file.name}</small>`;
				} else {
					previewHtml =
						`<i class="mdi mdi-file" style="font-size: 60px;"></i><br><small>${file.name}</small>`;
				}
				$('#newFilePreview').show()
					.find('div').html(
						previewHtml
					);
			};
			reader.readAsDataURL(file);
		} else {
			$('#newFilePreview').hide().find('div').empty();
		}
	});

	$('#editMediaForm').on('submit', function(e) {
		e.preventDefault();
		const id = $('#editMediaId').val();
		const formData = new FormData(this);
		formData.append('_method', 'PUT');

		$.ajax({
			url: '{{ route("cms.media.index") }}/' +
				id,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				if (response
					.success
				) {
					$('#editMediaModal')
						.modal(
							'hide'
						);
					$('#editMediaForm')[
							0
						]
						.reset();
					$('#newFilePreview')
						.hide()
						.find(
							'div'
						)
						.empty();
					// Reload the table to show updated media
					table.ajax.reload(null,
						false
					); // false = don't reset pagination
					Swal.fire({
						icon: 'success',
						title: '{{ __("cms.success") }}',
						text: response
							.message
					});
				}
			},
			error: function(xhr) {
				Swal.fire({
					icon: 'error',
					title: '{{ __("cms.error") }}',
					text: xhr.responseJSON
						?.message ||
						'{{ __("cms.an_error_occurred") }}'
				});
			}
		});
	});

	// Delete Media
	$(document).on('click', '.delete-media', function() {
		const id = $(this).data('id');

		Swal.fire({
			title: '{{ __("cms.are_you_sure") }}',
			text: '{{ __("cms.you_wont_be_able_to_revert_this") }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __("cms.yes_delete_it") }}'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '{{ route("cms.media.index") }}/' +
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
									.message
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
								?.message ||
								'{{ __("cms.an_error_occurred") }}'
						});
					}
				});
			}
		});
	});

	// Bulk Delete
	$('#bulkDeleteBtn').on('click', function() {
		const ids = $('.media-checkbox:checked').map(function() {
			return $(this).val();
		}).get();

		Swal.fire({
			title: '{{ __("cms.are_you_sure") }}',
			text: '{{ __("cms.you_are_about_to_delete") }} ' +
				ids.length +
				' {{ __("cms.media_files") }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __("cms.yes_delete_them") }}'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '{{ route("cms.media.bulk-delete") }}',
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						ids: ids
					},
					success: function(
						response
					) {
						if (response
							.success
						) {
							table.ajax
								.reload();
							$('#selectAll')
								.prop('checked',
									false
								);
							toggleBulkDeleteBtn
								();
							Swal.fire({
								icon: 'success',
								title: '{{ __("cms.deleted") }}',
								text: response
									.message
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
								?.message ||
								'{{ __("cms.an_error_occurred") }}'
						});
					}
				});
			}
		});
	});
});
</script>
@endpush
