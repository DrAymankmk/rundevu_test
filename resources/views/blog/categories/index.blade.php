@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:20px">
	<div class="row">
		<div class="col-12">
			<div class="page-title-box"
				style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="page-title">{{ __('blog.categories') }}</h4>
				<div class="page-title-right">
					<a href="{{ route('blog.categories.create') }}" class="btn btn-primary">
						<i class="mdi mdi-plus"></i> {{ __('blog.add_category') }}
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

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<table id="blog-categories-table" class="table dt-responsive nowrap w-100">
						<thead>
							<tr>
								<th>{{ __('blog.id') }}</th>
								<th>{{ __('blog.image') }}</th>
								<th>{{ __('blog.name') }}</th>
								<th>{{ __('blog.title') }}</th>
								<th>{{ __('blog.slug') }}</th>
								<th>{{ __('blog.posts_count') }}</th>
								<th>{{ __('blog.status') }}</th>
								<th>{{ __('blog.created_at') }}</th>
								<th>{{ __('blog.actions') }}</th>
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
	var table = $('#blog-categories-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "{{ route('blog.categories.data') }}",
			type: 'GET'
		},
		columns: [
			{ data: 'id', name: 'id' },
			{
				data: 'image',
				name: 'image',
				orderable: false,
				render: function(data) {
					if (!data) return '-';
					return '<img src="' + data + '" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">';
				}
			},
			{ data: 'name', name: 'name' },
			{ data: 'title', name: 'title', orderable: false },
			{ data: 'slug', name: 'slug' },
			{
				data: 'posts_count',
				name: 'posts_count',
				render: function(data) {
					return '<span class="badge bg-info">' + data + '</span>';
				}
			},
			{
				data: 'is_active',
				name: 'is_active',
				render: function(data, type, row) {
					var checked = data ? 'checked' : '';
					return '<div class="form-check form-switch"><input class="form-check-input toggle-status" type="checkbox" data-id="' + row.id + '" ' + checked + '></div>';
				}
			},
			{ data: 'created_at', name: 'created_at' },
			{
				data: null,
				orderable: false,
				render: function(data, type, row) {
					return `
						<a href="{{ url('admin/blog/categories') }}/${row.id}/edit" class="btn btn-sm btn-info">
							<i class="mdi mdi-pencil"></i>
						</a>
						<button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
							<i class="mdi mdi-delete"></i>
						</button>
					`;
				}
			}
		],
		order: [[0, 'desc']],
		language: languages[language] || languages['en']
	});

	$(document).on('click', '.delete-btn', function() {
		var id = $(this).data('id');
		Swal.fire({
			title: '{{ __("blog.are_you_sure") }}',
			text: '{{ __("blog.delete_category_confirm") }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __("blog.yes_delete") }}',
			cancelButtonText: '{{ __("blog.cancel") }}'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "{{ url('admin/blog/categories') }}/" + id,
					type: 'DELETE',
					data: { _token: '{{ csrf_token() }}' },
					success: function(response) {
						if (response.success) {
							table.ajax.reload();
							Swal.fire({
								icon: 'success',
								title: '{{ __("blog.deleted") }}',
								text: response.message,
								timer: 2000,
								showConfirmButton: false
							});
						}
					},
					error: function(xhr) {
						Swal.fire({
							icon: 'error',
							title: '{{ __("blog.error") }}',
							text: (xhr.responseJSON && xhr.responseJSON.message) || '{{ __("blog.an_error_occurred") }}'
						});
					}
				});
			}
		});
	});

	$(document).on('change', '.toggle-status', function() {
		var id = $(this).data('id');
		$.ajax({
			url: "{{ url('admin/blog/categories') }}/" + id + "/toggle-status",
			type: 'POST',
			data: { _token: '{{ csrf_token() }}' },
			success: function(response) {
				if (response.success) {
					Swal.fire({
						icon: 'success',
						title: '{{ __("blog.success") }}',
						text: response.message,
						timer: 1500,
						showConfirmButton: false
					});
				}
			},
			error: function(xhr) {
				table.ajax.reload();
				Swal.fire({
					icon: 'error',
					title: '{{ __("blog.error") }}',
					text: (xhr.responseJSON && xhr.responseJSON.message) || '{{ __("blog.an_error_occurred") }}'
				});
			}
		});
	});
});
</script>
@endpush
