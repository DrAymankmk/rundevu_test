@props([
	'title',
	'bg' => null,
	'page' => null,
	'items' => [],
	'current' => null,
])

@php
	// Banner files are listed and replaced from /admin/website-media (theme breadcrumb slots).
	$bg = $bg ?: frontend_breadcrumb_image($page);
	$current = $current ?? $title;
@endphp

<div {{ $attributes->merge(['class' => 'breadcumb-wrapper background-image']) }} @if($bg) style="background-image: url('{{ $bg }}');" @endif>
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title">{{ $title }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ frontend_route('frontend.home') }}">{{ __('main.home') }}</a></li>
				@foreach($items as $item)
				<li>
					@if(! empty($item['url']))
					<a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
					@else
					{{ $item['label'] }}
					@endif
				</li>
				@endforeach
				<li>{{ $current }}</li>
			</ul>
		</div>
	</div>
</div>
