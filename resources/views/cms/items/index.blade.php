@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:20px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box"
				style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="page-title">{{ __('cms.items') }}</h4>

				<div class="page-title-right">
					<a href="{{ route('cms.items.create', ['section_id' => $selectedSectionId]) }}"
						class="btn btn-primary">
						<i class="mdi mdi-plus"></i> {{ __('cms.add_item') }}
					</a>
				</div>
			</div>
		</div>
	</div>

	@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	@endif

	<!-- Filter -->
	<div class="row mb-3">
		<div class="col-md-4">
			<select id="section-filter" class="form-select">
				<option value="">{{ __('cms.all_sections') }}</option>
				@foreach($sections as $section)
				<option value="{{ $section->id }}"
					{{ $selectedSectionId == $section->id ? 'selected' : '' }}>
					{{ $section->page->name ?? '' }} - {{ $section->name }}
				</option>
				@endforeach
			</select>
		</div>
	</div>

	<!-- DataTable Card -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<table id="items-table" class="table dt-responsive nowrap w-100">
						<thead>
							<tr>
								<th>{{ __('cms.id') }}</th>
								<th>{{ __('cms.title') }}</th>
								<th>{{ __('cms.section') }}</th>
								<th>{{ __('cms.page') }}</th>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
	var table = $('#items-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "{{ route('cms.items.data') }}",
			type: 'GET',
			data: function(d) {
				d.section_id = $(
						'#section-filter'
					)
					.val();
			}
		},
		columns: [{
				data: 'id',
				name: 'id'
			},
			{
				data: 'title',
				name: 'title'
			},
			{
				data: 'section_name',
				name: 'section_name'
			},
			{
				data: 'page_name',
				name: 'page_name'
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
                        <a href="{{ url('admin/cms/items') }}/${row.id}" class="btn btn-sm btn-success">
                            <i class="mdi mdi-eye"></i>
                        </a>
                        <a href="{{ url('admin/cms/items') }}/${row.id}/edit" class="btn btn-sm btn-info">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    `;
				}
			}
		],
		order: [
			[6, 'asc']
		],
		language: languages[language] || languages['en']
	});

	// Section filter change
	$('#section-filter').on('change', function() {
		table.ajax.reload();
	});

	// Delete Button Click
	$(document).on('click', '.delete-btn', function() {
		var id = $(this).data('id');

		Swal.fire({
			title: '{{ __("Are you sure?") }}',
			text: '{{ __("This action cannot be undone!") }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __("Yes, delete it!") }}',
			cancelButtonText: '{{ __("Cancel") }}'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "{{ url('admin/cms/items') }}/" +
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
								title: '{{ __("Deleted!") }}',
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
							title: '{{ __("Error") }}',
							text: xhr.responseJSON
								.message ||
								'{{ __("An error occurred") }}'
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
			url: "{{ url('admin/cms/items') }}/" +
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
						title: '{{ __("Success") }}',
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
					title: '{{ __("Error") }}',
					text: xhr.responseJSON
						.message ||
						'{{ __("An error occurred") }}'
				});
			}
		});
	});
});
</script>
@endpush
