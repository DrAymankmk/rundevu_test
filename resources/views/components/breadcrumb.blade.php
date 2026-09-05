@props([
	'title',
	'bg' => asset('frontend/assets/img/section_bg.jpeg') ?? null,
])

<div {{ $attributes->merge(['class' => 'breadcumb-wrapper']) }} @if($bg) data-bg-src="{{ $bg }}" @endif>
	<div class="container">
		<div class="breadcumb-content">
			<h1 class="breadcumb-title">{{ $title }}</h1>
			<ul class="breadcumb-menu">
				<li><a href="{{ route('frontend.home') }}">{{ __('main.home') }}</a></li>
				<li>{{ $title }}</li>
			</ul>
		</div>
	</div>
</div>
