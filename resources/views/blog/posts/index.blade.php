@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper" style="padding:20px">
	<div class="row">
		<div class="col-12">
			<div class="page-title-box"
				style="display: flex; justify-content: space-between; align-items: center;">
				<h4 class="page-title">{{ __('blog.posts') }}</h4>
				<div class="page-title-right">
					<a href="{{ route('blog.posts.create') }}" class="btn btn-primary">
						<i class="mdi mdi-plus"></i> {{ __('blog.add_post') }}
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
					<div class="table-responsive">
						<table id="blog-posts-table" class="table table-hover w-100">
							<thead>
								<tr>
									<th>{{ __('blog.id') }}</th>
									<th>{{ __('blog.image') }}</th>
									<th>{{ __('blog.name') }}</th>
									<th>{{ __('blog.title') }}</th>
									<th>{{ __('blog.slug') }}</th>
									<th>{{ __('blog.categories') }}</th>
									<th>{{ __('blog.publish_date') }}</th>
									<th>{{ __('blog.status') }}</th>
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
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
	function escapeHtml(value) {
		if (value === null || value === undefined) {
			return '';
		}
		return String(value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	var table = $('#blog-posts-table').DataTable({
		processing: true,
		serverSide: true,
		autoWidth: false,
		scrollX: true,
		ajax: {
			url: "{{ route('blog.posts.data') }}",
			type: 'GET'
		},
		columns: [
			{ data: 'id', name: 'id', width: '50px' },
			{
				data: 'image',
				name: 'image',
				orderable: false,
				searchable: false,
				width: '70px',
				defaultContent: '-',
				render: function(data) {
					if (!data) {
						return '-';
					}
					return '<img src="' + escapeHtml(data) + '" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">';
				}
			},
			{
				data: 'name',
				name: 'name',
				defaultContent: '-',
				render: function(data) {
					return escapeHtml(data || '-');
				}
			},
			{
				data: 'title',
				name: 'title',
				orderable: false,
				defaultContent: '-',
				render: function(data) {
					return escapeHtml(data || '-');
				}
			},
			{
				data: 'slug',
				name: 'slug',
				defaultContent: '-',
				render: function(data) {
					return escapeHtml(data || '-');
				}
			},
			{
				data: 'categories',
				name: 'categories',
				orderable: false,
				defaultContent: '-',
				render: function(data) {
					return escapeHtml(data || '-');
				}
			},
			{
				data: 'publish_date',
				name: 'publish_date',
				defaultContent: '-',
				render: function(data) {
					return escapeHtml(data || '-');
				}
			},
			{
				data: 'is_active',
				name: 'is_active',
				orderable: false,
				searchable: false,
				width: '80px',
				render: function(data, type, row) {
					var checked = data ? 'checked' : '';
					return '<div class="form-check form-switch mb-0"><input class="form-check-input toggle-status" type="checkbox" data-id="' + row.id + '" ' + checked + '></div>';
				}
			},
			{
				data: 'id',
				name: 'actions',
				orderable: false,
				searchable: false,
				width: '100px',
				render: function(data) {
					return '' +
						'<a href="{{ url('admin/blog/posts') }}/' + data + '/edit" class="btn btn-sm btn-info me-1">' +
							'<i class="mdi mdi-pencil"></i>' +
						'</a>' +
						'<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' + data + '">' +
							'<i class="mdi mdi-delete"></i>' +
						'</button>';
				}
			}
		],
		order: [[0, 'desc']],
		language: (typeof languages !== 'undefined' && typeof language !== 'undefined' && languages[language])
			? languages[language]
			: (typeof languages !== 'undefined' ? languages['en'] : undefined)
	});

	$(document).on('click', '#blog-posts-table .delete-btn', function() {
		var id = $(this).data('id');
		Swal.fire({
			title: '{{ __("blog.are_you_sure") }}',
			text: '{{ __("blog.delete_post_confirm") }}',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: '{{ __("blog.yes_delete") }}',
			cancelButtonText: '{{ __("blog.cancel") }}'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "{{ url('admin/blog/posts') }}/" + id,
					type: 'DELETE',
					data: { _token: '{{ csrf_token() }}' },
					success: function(response) {
						if (response.success) {
							table.ajax.reload(null, false);
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

	$(document).on('change', '#blog-posts-table .toggle-status', function() {
		var id = $(this).data('id');
		$.ajax({
			url: "{{ url('admin/blog/posts') }}/" + id + "/toggle-status",
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
				table.ajax.reload(null, false);
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
