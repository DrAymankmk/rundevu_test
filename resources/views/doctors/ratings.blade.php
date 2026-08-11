@extends('includes_admin.mainlayout')
@section('content')

    <!-- Right sidebar Ends-->
    <div class="page-body ratings-page">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">
                                        <i data-feather="home"></i> {{ trans('admin.dashboard') }}
                                    </a></li>
                                <li class="breadcrumb-item active">@lang('admin.doctors_ratings') ({{ $ratings->total() }})</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-xl-3 col-lg-4">
                    <div class="rating-summary-card">
                        <span class="rating-summary-icon total"><i class="fa fa-list"></i></span>
                        <div>
                            <p>@lang('admin.all_ratings')</p>
                            <h3>{{ $summary['total'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 col-lg-4">
                    <div class="rating-summary-card">
                        <span class="rating-summary-icon average"><i class="fa fa-star"></i></span>
                        <div>
                            <p>@lang('admin.average_rating')</p>
                            <h3>{{ number_format($summary['average'] ?? 0, 1) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 col-lg-4">
                    <div class="rating-summary-card">
                        <span class="rating-summary-icon positive"><i class="fa fa-thumbs-up"></i></span>
                        <div>
                            <p>@lang('admin.positive_feedback')</p>
                            <h3>{{ $summary['positive_percentage'] ?? 0 }}%</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 col-lg-4">
                    <div class="rating-summary-card">
                        <span class="rating-summary-icon comments"><i class="fa fa-comments"></i></span>
                        <div>
                            <p>@lang('admin.ratings_with_comments')</p>
                            <h3>{{ $summary['with_comments'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="col-sm-12">
                    <div class="card filter-card">
                        <div class="card-header">
                            <h5><i class="fa fa-filter"></i> @lang('admin.filter_ratings')</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.doctors.ratings') }}" method="GET" class="row filter-form align-items-end">
                                <div class="col-lg-5 col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="filter-label">@lang('admin.search') @lang('admin.doctor_name')</label>
                                        <input type="text" name="search" class="form-control"
                                               value="{{ request('search') }}"
                                               placeholder="@lang('admin.enter_doctor_name')">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="filter-label">@lang('admin.sort_by')</label>
                                        <select name="order" class="form-control">
                                            <option value="">@lang('admin.default')</option>
                                            <option value="top" {{ request('order') == 'top' ? 'selected' : '' }}>
                                                @lang('admin.highest_rated')
                                            </option>
                                            <option value="newest" {{ request('order') == 'newest' ? 'selected' : '' }}>
                                                @lang('admin.newest')
                                            </option>
                                            <option value="oldest" {{ request('order') == 'oldest' ? 'selected' : '' }}>
                                                @lang('admin.oldest')
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12 filter-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> @lang('admin.search')
                                    </button>
                                    <a href="{{ route('admin.doctors.ratings') }}" class="btn btn-secondary">
                                        <i class="fa fa-refresh"></i> @lang('admin.reset')
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Ratings Table -->
                <div class="col-sm-12">
                    <div class="card">
                        @if($ratings->count() > 0)
                            <div class="card-header ratings-list-header">
                                <div class="ratings-list-title">
                                    <h5><i class="fa fa-list"></i> @lang('admin.doctors_ratings_list')
                                        <span class="count-pill">{{ $ratings->total() }}</span>
                                    </h5>
                                </div>
                                <div class="ratings-list-stats">
                                    <div class="stat-chip">
                                        <i class="fa fa-star text-warning"></i>
                                        <span class="stat-label">@lang('admin.average_rating')</span>
                                        <span class="badge badge-success">{{ number_format($summary['average'] ?? 0, 1) }}</span>
                                    </div>
                                    <div class="stat-chip">
                                        <i class="fa fa-thumbs-up text-info"></i>
                                        <span class="stat-label">@lang('admin.positive_feedback')</span>
                                        <span class="badge badge-info">{{ $summary['positive_percentage'] ?? 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-2">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('admin.doctor_name')</th>
                                            <th>@lang('admin.patient')</th>
                                            <th>@lang('admin.rating')</th>
                                            <th>@lang('admin.comment')</th>
                                            <th>@lang('admin.date')</th>
{{--                                            <th>@lang('admin.action')</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($ratings as $index => $rating)
                                            @php
                                                $doctor = $rating->doctors ?? null;
                                                $patient = $rating->users ?? null;
                                                $reservation = $rating->reservations ?? null;
                                                $ratingValue = $rating->rate_value ?? 0;
                                                $ratingDate = $rating->created_at ? \Carbon\Carbon::parse($rating->created_at) : null;
                                                $ratingStatus = $rating->status ?? 1;
                                            @endphp
                                            <tr>
                                                <td>{{ ($ratings->currentPage() - 1) * $ratings->perPage() + $index + 1 }}</td>
                                                <td>
                                                    @if($doctor)
                                                        <div class="d-flex align-items-center">
                                                            @if($doctor->image)
                                                                <img src="{{ $doctor->image }}"
                                                                     class="rounded-circle mr-2"
                                                                     width="40" height="40"
                                                                     alt="{{ $doctor->name }}"
                                                                     style="object-fit: cover;">
                                                            @else
                                                                <div class="avatar mr-2">
                                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                         style="width: 40px; height: 40px; font-size: 16px;">
                                                                        {{ substr($doctor->name, 0, 1) }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <strong>{{ $doctor->name }}</strong><br>
                                                                <small class="text-muted" style="font-size: 12px;">
{{--                                                                    {{ $reservation->specialty->name ?? null }}--}}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">@lang('admin.doctor_deleted')</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($patient)
                                                        <div class="d-flex align-items-center">
                                                            @if($patient->image)
                                                                <img src="{{  $patient->image }}"
                                                                     class="rounded-circle mr-2"
                                                                     width="40" height="40"
                                                                     alt="{{ $patient->name }}"
                                                                     style="object-fit: cover;">
                                                            @else
                                                                <div class="avatar mr-2">
                                                                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center"
                                                                         style="width: 40px; height: 40px; font-size: 16px;">
                                                                        {{ substr($patient->name, 0, 1) }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <strong>{{ $patient->name }}</strong><br>
                                                                <small class="text-muted" style="font-size: 12px;">
                                                                    {{ $patient->phone ?? $patient->email }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">@lang('admin.patient_deleted')</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="rating-cell">
                                                        <div class="rating-stars">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $ratingValue)
                                                                    <i class="fa fa-star text-warning"></i>
                                                                @else
                                                                    <i class="fa fa-star-o text-warning"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span class="rating-badge">{{ number_format($ratingValue, 1) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($rating->comment)
                                                        <div class="comment-box" style="max-height: 60px; overflow-y: auto; cursor: pointer;"
                                                             data-toggle="popover"
                                                             data-placement="left"
                                                             data-content="{{ $rating->comment }}"
                                                             data-trigger="hover">
                                                            {{ Str::limit($rating->comment, 50) }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">@lang('admin.no_comment')</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($ratingDate)
                                                        <span class="badge badge-light">
                                                            {{ $ratingDate->format('Y-m-d') }}
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $ratingDate->format('h:i A') }}
                                                        </small>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>

{{--                                                <td>--}}
{{--                                                    <div class="btn-group" role="group">--}}
{{--                                                        <button class="btn btn-info btn-sm view-rating"--}}
{{--                                                                data-rating-id="{{ $rating->id }}"--}}
{{--                                                                data-toggle="modal"--}}
{{--                                                                data-target="#viewRatingModal">--}}
{{--                                                            <i class="fa fa-eye"></i>--}}
{{--                                                        </button>--}}

{{--                                                    </div>--}}
{{--                                                </td>--}}
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                {{ $ratings->links() }}
                            </div>
                        @else
                            <div class="card-body text-center py-5">
                                <div class="empty-state">
                                    <i class="fa fa-star-o fa-4x text-muted mb-3"></i>
                                    <h4 class="text-muted">@lang('admin.no_ratings_found')</h4>
                                    <p class="text-muted">@lang('admin.no_ratings_description')</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Rating Details -->
    <div class="modal fade" id="viewRatingModal" tabindex="-1" role="dialog" aria-labelledby="viewRatingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRatingModalLabel">@lang('admin.rating_details')</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="ratingModalBody">
                    <!-- Content will be loaded by AJAX -->
                    <div class="text-center py-4">
                        <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">@lang('admin.loading')...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        @lang('admin.close')
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ========= Scoped styles for the ratings page only ========= --}}
    <style>
        .ratings-page .card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .ratings-page .card-header {
            background: #fff;
            border-bottom: 1px solid #eef0f4;
            padding: 14px 20px;
        }
        .ratings-page .card-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1f2a44;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .ratings-page .card-header h5 i { color: #2E37A4; }
        .ratings-page .card-body { padding: 20px; }

        .ratings-page .rating-summary-card {
            min-height: 116px;
            background: #fff;
            border: 1px solid #eef0f4;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            padding: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .ratings-page .rating-summary-card p {
            margin: 0 0 6px;
            color: #667085;
            font-size: 13px;
            font-weight: 600;
        }
        .ratings-page .rating-summary-card h3 {
            margin: 0;
            color: #1f2a44;
            font-size: 26px;
            line-height: 1.1;
            font-weight: 800;
        }
        .ratings-page .rating-summary-icon {
            width: 48px;
            min-width: 48px;
            height: 48px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .ratings-page .rating-summary-icon.total {
            color: #2E37A4;
            background: rgba(46, 55, 164, .1);
        }
        .ratings-page .rating-summary-icon.average {
            color: #b54708;
            background: #fff7e6;
        }
        .ratings-page .rating-summary-icon.positive {
            color: #027a48;
            background: #ecfdf3;
        }
        .ratings-page .rating-summary-icon.comments {
            color: #175cd3;
            background: #eff8ff;
        }

        /* ---------- Filter card ---------- */
        .ratings-page .filter-form { row-gap: 12px; }
        .ratings-page .filter-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #475467;
            margin-bottom: 6px;
        }
        .ratings-page .filter-form .form-control {
            height: 42px;
            border-radius: 10px;
            border: 1px solid #e3e6ee;
            font-size: 14px;
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }
        .ratings-page .filter-form .form-control:focus {
            border-color: #2E37A4;
            box-shadow: 0 0 0 4px rgba(46, 55, 164, .08);
            outline: none;
        }
        .ratings-page .filter-actions {
            display: flex;
            gap: 8px;
            align-items: stretch;
        }
        .ratings-page .filter-actions .btn {
            height: 42px;
            border-radius: 10px;
            padding: 0 18px;
            font-weight: 600;
            font-size: 13.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            line-height: 1;
        }
        .ratings-page .filter-actions .btn-primary {
            background: linear-gradient(135deg, #2E37A4 0%, #0E9384 100%);
            border: 0;
            box-shadow: 0 4px 10px rgba(46, 55, 164, .18);
            flex: 1 1 auto;
            justify-content: center;
        }
        .ratings-page .filter-actions .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(46, 55, 164, .25);
        }
        .ratings-page .filter-actions .btn-secondary {
            background: #f3f4f8;
            color: #475467;
            border: 1px solid #e3e6ee;
        }
        .ratings-page .filter-actions .btn-secondary:hover {
            background: #e9ebf2;
            color: #1f2a44;
        }

        /* ---------- Ratings list header (title + stats) ---------- */
        .ratings-page .ratings-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }
        .ratings-page .count-pill {
            display: inline-block;
            background: rgba(46, 55, 164, .1);
            color: #2E37A4;
            font-size: 12px;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 50rem;
            margin-inline-start: 6px;
        }
        .ratings-page .ratings-list-stats {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .ratings-page .stat-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f7f8fb;
            border: 1px solid #eef0f4;
            border-radius: 50rem;
            padding: 6px 12px;
            font-size: 13px;
        }
        .ratings-page .stat-chip .stat-label {
            color: #667085;
            font-weight: 500;
        }
        .ratings-page .stat-chip .badge {
            font-weight: 700;
            padding: 4px 9px;
            border-radius: 50rem;
            font-size: 12px;
        }
        .ratings-page .stat-chip .badge-success {
            background: #ecfdf3;
            color: #027a48;
        }
        .ratings-page .stat-chip .badge-info {
            background: #eff8ff;
            color: #175cd3;
        }

        /* ---------- DataTable wrapper / controls ---------- */
        .ratings-page .dataTables_wrapper {
            padding: 0;
        }
        .ratings-page .dataTables_wrapper .dataTables_length,
        .ratings-page .dataTables_wrapper .dataTables_filter {
            margin-bottom: 14px;
        }
        .ratings-page .dataTables_wrapper .dataTables_length select,
        .ratings-page .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e3e6ee !important;
            border-radius: 8px !important;
            padding: 6px 10px !important;
            font-size: 13.5px;
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
            min-width: 120px;
        }
        .ratings-page .dataTables_wrapper .dataTables_filter input:focus,
        .ratings-page .dataTables_wrapper .dataTables_length select:focus {
            border-color: #2E37A4 !important;
            box-shadow: 0 0 0 3px rgba(46, 55, 164, .08) !important;
            outline: none;
        }
        .ratings-page .dataTables_wrapper .dataTables_filter label,
        .ratings-page .dataTables_wrapper .dataTables_length label {
            color: #475467;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* ---------- Table ---------- */
        .ratings-page table.dataTable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
        }
        .ratings-page table.dataTable thead th {
            background: #f7f8fb;
            color: #475467;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0;
            border: 0;
            padding: 14px 12px;
        }
        .ratings-page table.dataTable tbody td {
            padding: 14px 12px;
            border-top: 1px solid #f2f4f7;
            vertical-align: middle;
            font-size: 13.5px;
            color: #344054;
        }
        .ratings-page table.dataTable tbody tr:hover {
            background: rgba(46, 55, 164, .03);
        }

        /* ---------- Doctor / Patient cell (RTL-safe avatar spacing) ---------- */
        .ratings-page table.dataTable tbody td .d-flex.align-items-center > .mr-2,
        .ratings-page table.dataTable tbody td .d-flex.align-items-center > .avatar.mr-2 {
            margin-right: 0 !important;
            margin-inline-end: 10px !important;
        }
        .ratings-page table.dataTable tbody td strong {
            color: #1f2a44;
            font-size: 14px;
            font-weight: 600;
        }
        .ratings-page table.dataTable tbody td .text-muted {
            color: #98a2b3 !important;
        }

        /* ---------- Rating cell ---------- */
        .ratings-page .rating-cell {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .ratings-page .rating-stars i {
            font-size: 14px;
            margin-inline-end: 2px;
        }
        .ratings-page .rating-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 26px;
            padding: 0 10px;
            border-radius: 50rem;
            background: linear-gradient(135deg, #fff7e6 0%, #fef0c7 100%);
            color: #b54708;
            font-weight: 700;
            font-size: 12.5px;
            border: 1px solid #fedf89;
        }

        /* ---------- Comment cell ---------- */
        .ratings-page .comment-box {
            max-width: 260px;
            color: #475467;
            line-height: 1.5;
        }

        /* ---------- Date cell ---------- */
        .ratings-page table.dataTable tbody td .badge.badge-light {
            background: #eef0f4;
            color: #475467;
            font-weight: 600;
            padding: 4px 9px;
            border-radius: 6px;
            font-size: 12px;
        }

        /* ---------- Card footer / pagination ---------- */
        .ratings-page .card-footer {
            background: #fafbfd;
            border-top: 1px solid #eef0f4;
            padding: 14px 20px;
        }
        .ratings-page .pagination {
            justify-content: flex-end;
            margin: 0;
            gap: 4px;
        }
        .ratings-page .pagination .page-item .page-link {
            border-radius: 8px !important;
            border: 1px solid #e3e6ee;
            color: #475467;
            font-weight: 600;
            font-size: 13px;
            padding: 6px 12px;
            margin: 0;
        }
        .ratings-page .pagination .page-item.active .page-link {
            background: #2E37A4;
            border-color: #2E37A4;
            color: #fff;
        }
        .ratings-page .pagination .page-item.disabled .page-link {
            color: #cbd1dc;
            background: #fff;
        }

        /* ---------- Empty state ---------- */
        .ratings-page .empty-state h4 { color: #475467; font-weight: 600; }
        .ratings-page .empty-state p  { color: #98a2b3; }

        /* ---------- Responsive ---------- */
        @media (max-width: 767.98px) {
            .ratings-page .ratings-list-header { flex-direction: column; align-items: flex-start; }
            .ratings-page .filter-actions { margin-top: 8px; }
            .ratings-page .pagination { justify-content: center; }
        }
    </style>

    {{-- replace bootstrap popover (data-toggle) with hover tooltip-like behavior --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery && jQuery.fn.popover) {
                jQuery('[data-toggle="popover"]').popover({ trigger: 'hover', html: true });
            }
        });
    </script>

@endsection

{{-- ============================================================
     Inject into <head> via @yield('styles') in the admin layout.
     This loads a refined Arabic/Latin font (Tajawal + Cairo) for
     the ratings page without overriding icon fonts in the sidebar.
============================================================ --}}
@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --tj-font: 'Tajawal', 'Cairo', 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .ratings-page,
        .ratings-page h1,
        .ratings-page h2,
        .ratings-page h3,
        .ratings-page h4,
        .ratings-page h5,
        .ratings-page h6,
        .ratings-page p,
        .ratings-page span:not(.fa):not(.fa-solid):not(.fas):not(.far),
        .ratings-page a,
        .ratings-page table,
        .ratings-page label,
        .ratings-page input,
        .ratings-page select,
        .ratings-page button,
        .ratings-page textarea,
        .modal,
        .modal *:not(.fa):not(.fa-solid):not(.fas):not(.far),
        .dropdown-menu,
        .dropdown-menu *:not(.fa):not(.fa-solid):not(.fas):not(.far) {
            font-family: var(--tj-font) !important;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-feature-settings: "kern" 1, "liga" 1;
        }

        .ratings-page h1,
        .ratings-page h2,
        .ratings-page h3,
        .ratings-page h4,
        .ratings-page h5,
        .ratings-page h6 {
            letter-spacing: 0;
            font-weight: 700;
        }

        .ratings-page .card-header h5,
        .ratings-page h4,
        .ratings-page h3 {
            font-weight: 700;
        }

        .ratings-page table thead th {
            font-weight: 700;
            letter-spacing: 0;
        }
    </style>
@endsection
