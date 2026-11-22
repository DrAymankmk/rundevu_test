@if($links->isEmpty())
	<div class="alert alert-info mb-0">{{ $emptyText }}</div>
@else
	<div class="table-responsive">
		<table class="table border-0 custom-table comman-table mb-0">
			<thead>
				<tr>
					<th>{{ __('sitemap_links.name') }}</th>
					<th>{{ __('sitemap_links.path') }}</th>
					<th>{{ __('sitemap_links.changefreq') }}</th>
					<th>{{ __('sitemap_links.priority') }}</th>
					<th>{{ __('sitemap_links.lastmod') }}</th>
					<th>{{ __('sitemap_links.include_alternates') }}</th>
					<th>{{ __('sitemap_links.x_default_locale') }}</th>
					<th>{{ __('sitemap_links.sort_order') }}</th>
					<th>{{ __('sitemap_links.status') }}</th>
					<th>{{ __('admin.options') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($links as $link)
				<tr>
					<td class="fw-semibold">
						{{ $link->title }}
						@if($link->isGroup())
							<span class="badge bg-light text-dark ms-1">{{ __('sitemap_links.type_group') }}</span>
						@endif
					</td>
					<td>
						@if($link->isGroup())
							<span class="text-muted">{{ __('sitemap_links.no_path') }} ({{ $link->group_key }})</span>
						@else
							<code>{{ $link->path }}</code>
						@endif
					</td>
					<td>{{ $link->changefreq }}</td>
					<td>{{ number_format((float) $link->priority, 1) }}</td>
					<td>
						@if($link->lastmod)
							{{ $link->lastmod->format('Y-m-d H:i') }}
						@else
							<span class="text-muted">auto</span>
						@endif
					</td>
					<td>
						@if($link->include_alternates)
							<span class="badge bg-success-subtle text-success">ON</span>
						@else
							<span class="badge bg-secondary-subtle text-secondary">OFF</span>
						@endif
					</td>
					<td>
						@php
							$xDefault = $link->x_default_locale ?: \App\Models\SitemapLink::X_DEFAULT_AUTO;
							$xDefaultLabel = \App\Models\SitemapLink::xDefaultOptions()[$xDefault] ?? $xDefault;
						@endphp
						{{ $xDefaultLabel }}
					</td>
					<td>{{ $link->sort_order }}</td>
					<td>
						<form action="{{ route('sitemap-links.toggle-status', $link->id) }}" method="POST" class="d-inline">
							@csrf
							<button type="submit" class="btn btn-sm {{ $link->is_active ? 'btn-success' : 'btn-secondary' }}">
								{{ $link->is_active ? __('sitemap_links.active') : __('sitemap_links.inactive') }}
							</button>
						</form>
					</td>
					<td>
						<a href="{{ route('sitemap-links.edit', $link->id) }}" class="btn btn-sm btn-outline-primary">
							<i class="fa fa-pen-to-square"></i>
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endif
