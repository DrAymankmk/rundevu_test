@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:20px">
	<!-- Page Title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box"
				style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="page-title">{{ __('cms.pages') }}</h4>

				<div class="page-title-right">
					<a href="{{ route('cms.pages.builder.create') }}"
						class="btn btn-primary">
						<i class="mdi mdi-view-dashboard-variant"></i>
						{{ __('cms.page_builder') }}
					</a>
					<a href="{{ route('cms.pages.create') }}"
						class="btn btn-outline-primary">
						<i class="mdi mdi-plus"></i> {{ __('cms.add_page') }}
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

	<!-- DataTable Card -->
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<table id="pages-table" class="table dt-responsive nowrap w-100">
						<thead>
							<tr>
								<th>{{ __('cms.id') }}</th>
								<th>{{ __('cms.name') }}</th>
								<th>{{ __('cms.slug') }}</th>
								<th>{{ __('cms.title') }}</th>
								<th>{{ __('cms.sections_count') }}</th>
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
	var table = $('#pages-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "{{ route('cms.pages.data') }}",
			type: 'GET'
		},
		columns: [{
				data: 'id',
				name: 'id'
			},
			{
				data: 'name',
				name: 'name'
			},
			{
				data: 'slug',
				name: 'slug'
			},
			{
				data: 'title',
				name: 'title'
			},
			{
				data: 'sections_count',
				name: 'sections_count',
				render: function(data) {
					return '<span class="badge bg-info">' +
						data +
						'</span>';
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
                        <a href="{{ url('admin/cms/pages') }}/${row.id}" class="btn btn-sm btn-success">
                            <i class="mdi mdi-eye"></i>
                        </a>
                        <a href="{{ url('admin/cms/pages') }}/${row.id}/builder" class="btn btn-sm btn-primary" title="{{ __('Page builder') }}">
                            <i class="mdi mdi-view-dashboard-variant"></i>
                        </a>
                        <a href="{{ url('admin/cms/pages') }}/${row.id}/edit" class="btn btn-sm btn-info">
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

	// Delete Button Click
	$(document).on('click', '.delete-btn', function() {
		var id = $(this).data('id');

		Swal.fire({
			title: '{{ __("Are you sure?") }}',
			text: '{{ __("This will also delete all sections and items!") }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __("Yes, delete it!") }}',
			cancelButtonText: '{{ __("Cancel") }}'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "{{ url('admin/cms/pages') }}/" +
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
			url: "{{ url('admin/cms/pages') }}/" +
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
