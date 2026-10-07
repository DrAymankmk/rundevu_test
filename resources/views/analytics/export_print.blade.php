<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}" dir="{{ $dir ?? 'ltr' }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $title ?? 'Export' }}</title>
	<style>
		:root {
			--text: #1f2937;
			--muted: #6b7280;
			--border: #d1d5db;
			--head: #f3f4f6;
		}

		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 24px;
			color: var(--text);
			font-family: "Segoe UI", Tahoma, Arial, sans-serif;
			font-size: 13px;
			line-height: 1.45;
			background: #fff;
		}

		.toolbar {
			display: flex;
			gap: 8px;
			align-items: center;
			justify-content: space-between;
			margin-bottom: 16px;
		}

		.toolbar h1 {
			margin: 0;
			font-size: 20px;
			font-weight: 700;
		}

		.toolbar .meta {
			color: var(--muted);
			font-size: 12px;
		}

		.actions {
			display: flex;
			gap: 8px;
		}

		.btn {
			appearance: none;
			border: 1px solid var(--border);
			background: #fff;
			color: var(--text);
			border-radius: 6px;
			padding: 8px 12px;
			font-size: 13px;
			cursor: pointer;
			text-decoration: none;
		}

		.btn-primary {
			background: #2563eb;
			border-color: #2563eb;
			color: #fff;
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		th,
		td {
			border: 1px solid var(--border);
			padding: 8px 10px;
			text-align: start;
			vertical-align: top;
			word-break: break-word;
		}

		th {
			background: var(--head);
			font-weight: 600;
		}

		tr:nth-child(even) td {
			background: #fafafa;
		}

		.empty {
			padding: 24px;
			text-align: center;
			color: var(--muted);
			border: 1px dashed var(--border);
			border-radius: 8px;
		}

		@media print {
			body {
				padding: 0;
			}

			.toolbar .actions {
				display: none !important;
			}

			th {
				background: #eee !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
			}
		}
	</style>
</head>
<body>
	<div class="toolbar">
		<div>
			<h1>{{ $title ?? 'Export' }}</h1>
			<div class="meta">{{ now()->format('Y-m-d H:i') }}</div>
		</div>
		<div class="actions">
			<button type="button" class="btn btn-primary" onclick="window.print()">
				{{ ($locale ?? app()->getLocale()) === 'ar' ? 'طباعة' : 'Print' }}
			</button>
			<button type="button" class="btn" onclick="window.close()">
				{{ ($locale ?? app()->getLocale()) === 'ar' ? 'إغلاق' : 'Close' }}
			</button>
		</div>
	</div>

	@php
		$headerList = array_values($headers ?? []);
		$rowList = $rows ?? [];
	@endphp

	@if (empty($headerList))
		<div class="empty">{{ ($locale ?? app()->getLocale()) === 'ar' ? 'لا توجد بيانات' : 'No data' }}</div>
	@else
		<table>
			<thead>
				<tr>
					@foreach ($headerList as $header)
						<th>{{ $header }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@forelse ($rowList as $row)
					@php
						if ($row instanceof \Illuminate\Support\Collection) {
							$values = $row->values()->all();
						} elseif (is_object($row)) {
							$values = array_values((array) $row);
						} else {
							$values = array_values((array) $row);
						}

						$count = count($headerList);
						if (count($values) > $count) {
							$values = array_slice($values, 0, $count);
						}
						while (count($values) < $count) {
							$values[] = '';
						}
					@endphp
					<tr>
						@foreach ($values as $value)
							<td>{{ $value }}</td>
						@endforeach
					</tr>
				@empty
					<tr>
						<td colspan="{{ count($headerList) }}">
							{{ ($locale ?? app()->getLocale()) === 'ar' ? 'لا توجد بيانات' : 'No data' }}
						</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	@endif

	@if (!empty($autoPrint))
		<script>
			window.addEventListener('load', function () {
				setTimeout(function () {
					window.print();
				}, 250);
			});
		</script>
	@endif
</body>
</html>
