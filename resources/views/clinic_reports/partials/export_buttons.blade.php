@php
$exportSection = $exportSection ?? ($page ?? 'appointments');
$query = request()->query();
$exportRoute = route('clinic-reports.export', ['section' => $exportSection]);
@endphp
<div class="btn-group mb-3" style="float: left; display: flex; gap: 20px;">
	<a class="btn btn-outline-secondary btn-sm"
		href="{{ $exportRoute }}?{{ http_build_query(array_merge($query, ['format' => 'csv'])) }}">@lang('clinic_reports.export_csv')</a>
	<!-- <a class="btn btn-outline-success btn-sm"
       href="{{ $exportRoute }}?{{ http_build_query(array_merge($query, ['format' => 'excel'])) }}">@lang('clinic_reports.export_excel')</a> -->
	<a class="btn btn-outline-danger btn-sm" target="_blank"
		href="{{ $exportRoute }}?{{ http_build_query(array_merge($query, ['format' => 'pdf'])) }}">@lang('clinic_reports.export_pdf')</a>
	<a class="btn btn-outline-primary btn-sm" target="_blank"
		href="{{ $exportRoute }}?{{ http_build_query(array_merge($query, ['format' => 'print'])) }}">@lang('clinic_reports.export_print')</a>
</div>
