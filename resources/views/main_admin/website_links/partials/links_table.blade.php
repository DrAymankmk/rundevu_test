@if($links->isEmpty())
	<div class="alert alert-info mb-0">{{ $emptyText }}</div>
@else
	<div class="table-responsive">
		<table class="table border-0 custom-table comman-table mb-0">
			<thead>
				<tr>
					<th>{{ __('website_links.preview') }}</th>
					<th>{{ __('website_links.name') }}</th>
					<th>{{ __('website_links.key') }}</th>
					<th>{{ __('website_links.url') }}</th>
					<th>{{ __('website_links.sort_order') }}</th>
					<th>{{ __('website_links.status') }}</th>
					<th>{{ __('admin.options') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($links as $link)
				@php
					$color = $link->brand_color ?: '#3E66F3';
				@endphp
				<tr>
					<td>
						<span class="d-inline-flex align-items-center justify-content-center rounded-circle"
							style="width:42px;height:42px;background:{{ $color }};color:#fff;">
							<i class="{{ $link->icon ?: 'fas fa-share-alt' }}"></i>
						</span>
					</td>
					<td class="fw-semibold">{{ $link->displayTitle() }}</td>
					<td><code>{{ $link->key }}</code></td>
					<td>
						@if(filled($link->url))
							<a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
								{{ \Illuminate\Support\Str::limit($link->url, 48) }}
								<i class="fa-regular fa-arrow-up-right-from-square ms-1"></i>
							</a>
						@else
							<span class="text-muted">{{ __('website_links.no_url') }}</span>
						@endif
					</td>
					<td>{{ $link->sort_order }}</td>
					<td>
						<form action="{{ route('website-links.toggle-status', $link->id) }}" method="POST" class="d-inline">
							@csrf
							<button type="submit" class="btn btn-sm {{ $link->is_active ? 'btn-success' : 'btn-secondary' }}">
								{{ $link->is_active ? __('website_links.active') : __('website_links.inactive') }}
							</button>
						</form>
					</td>
					<td>
						<div class="d-flex gap-2">
							<a href="{{ route('website-links.edit', $link->id) }}" class="btn btn-sm btn-outline-primary">
								<i class="fa fa-pen-to-square"></i>
							</a>
							<button type="button" class="btn btn-sm btn-outline-danger"
								data-bs-toggle="modal" data-bs-target="#delete_link_{{ $link->id }}">
								<i class="fa fa-trash-can"></i>
							</button>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endif
