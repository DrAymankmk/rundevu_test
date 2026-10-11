@php
	$logoAlt = $logoAlt ?? config('app.name', 'Randevu');
	$logoWidth = (int) ($logoWidth ?? 100);
	$logoHeight = (int) ($logoHeight ?? 50);
	$logoClass = $logoClass ?? '';
	$logoStyle = $logoStyle ?? "height:{$logoHeight}px; width:{$logoWidth}px;";
	$logoLoading = $logoLoading ?? null;
	$logoFetchPriority = $logoFetchPriority ?? null;
	// Source of truth: Dashboard → Website Media → Website logo (theme:logo).
	$logoPng = frontend_logo_url('png');
	$logoWebpPath = public_path('frontend/assets/img/logo.webp');
	$logoWebp = is_file($logoWebpPath) ? frontend_logo_url('webp') : null;
@endphp
@if($logoWebp)
<picture>
	<source srcset="{{ $logoWebp }}" type="image/webp">
	<img
		src="{{ $logoPng }}"
		width="{{ $logoWidth }}"
		height="{{ $logoHeight }}"
		style="{{ $logoStyle }}"
		@if($logoClass !== '') class="{{ $logoClass }}" @endif
		@if($logoFetchPriority) fetchpriority="{{ $logoFetchPriority }}" @endif
		@if($logoLoading) loading="{{ $logoLoading }}" @endif
		decoding="async"
		alt="{{ $logoAlt }}">
</picture>
@else
<img
	src="{{ $logoPng }}"
	width="{{ $logoWidth }}"
	height="{{ $logoHeight }}"
	style="{{ $logoStyle }}"
	@if($logoClass !== '') class="{{ $logoClass }}" @endif
	@if($logoFetchPriority) fetchpriority="{{ $logoFetchPriority }}" @endif
	@if($logoLoading) loading="{{ $logoLoading }}" @endif
	decoding="async"
	alt="{{ $logoAlt }}">
@endif
