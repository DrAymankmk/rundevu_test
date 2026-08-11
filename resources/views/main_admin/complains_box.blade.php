<?php $page = 'complains-box'; ?>
@extends('layout_new.mainlayout')
@section('content')

    @php
        $allComplaints = collect($complains_box);
    @endphp

    <style>
        .complaints-tabs-wrap{
            margin-bottom: 18px;
        }
        .complaints-tabs{
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
        }
        .complaints-tab-btn{
            border:1px solid #dbe0e6;
            background:#fff;
            color:#212529;
            border-radius:12px;
            padding:10px 16px;
            font-size:14px;
            font-weight:600;
            display:inline-flex;
            align-items:center;
            gap:8px;
            transition:all .2s ease;
            cursor:pointer;
        }
        .complaints-tab-btn:hover{
            border-color:#0d6efd;
            color:#0d6efd;
        }
        .complaints-tab-btn.active{
            background:#0d6efd;
            border-color:#0d6efd;
            color:#fff;
            box-shadow:0 8px 20px rgba(13,110,253,.18);
        }
        .complaints-tab-btn .tab-count{
            background:rgba(255,255,255,.18);
            border:1px solid rgba(255,255,255,.25);
            color:inherit;
            border-radius:999px;
            padding:2px 8px;
            font-size:12px;
            line-height:1.4;
        }
        .complaints-tab-btn:not(.active) .tab-count{
            background:#f1f3f5;
            border-color:#e9ecef;
            color:#495057;
        }
        .complaints-empty-state{
            display:none;
            padding:22px;
            text-align:center;
            background:#fff;
            border:1px dashed #dbe0e6;
            border-radius:16px;
            color:#6c757d;
            font-weight:600;
            margin-top:12px;
        }
        .truncate-text{
            max-width:260px;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
        @media (max-width: 767px){
            .complaints-tab-btn{
                width:100%;
                justify-content:center;
            }
            .truncate-text{
                max-width:180px;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h4 class="fw-bold mb-0 me-2">@lang('main.Complaints Box')</h4>
                    <span class="badge badge-soft-primary border pt-1 px-2 border-primary fw-medium">
                        @lang('main.Total Complaints Box') : {{ count($complains_box) }}
                    </span>
                </div>
            </div>

            <div class="complaints-tabs-wrap">
                <div class="complaints-tabs">
                    <button type="button" class="complaints-tab-btn active" data-tab="all">
                        <span>@lang('admin.show all')</span>
                        <span class="tab-count">{{ count($complains_box) }}</span>
                    </button>

                    <button type="button" class="complaints-tab-btn" data-tab="user">
                        <span>@lang('admin.users')</span>
                        <span class="tab-count">{{ $usersCount }}</span>
                    </button>

                    <button type="button" class="complaints-tab-btn" data-tab="clinic">
                        <span>@lang('main.clinic')</span>
                        <span class="tab-count">{{ $clinicsCount }}</span>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set mb-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="table-search d-flex align-items-center mb-0">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn-searchset"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex table-dropdown mb-3 pb-1 right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-filter me-2 fs-14"></i>@lang('admin.Filter')
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown" id="filter-dropdown">
                            <div class="d-flex align-items-center justify-content-between border-bottom filter-header">
                                <h4 class="fs-18 fw-bold">@lang('admin.Filter')</h4>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-danger text-decoration-underline me-3">@lang('admin.Clear All')</a>
                                </div>
                            </div>
                            <form action="#">
                                <div class="filter-body pb-1">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">@lang('admin.Date')</label>
                                        </div>
                                        <div class="input-group position-relative">
                                            <input type="text" class="form-control date-range bookingrange rounded-end h-auto py-2 bg-white">
                                            <span class="input-icon-addon fs-16 text-gray-9">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-footer d-flex align-items-center justify-content-end border-top">
                                    <a href="javascript:void(0);" class="btn btn-light btn-md me-2" id="close-filter">@lang('main.Close')</a>
                                    <button type="submit" class="btn btn-primary btn-md">@lang('main.Filter')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14" data-bs-toggle="dropdown">
                            <span class="me-1">@lang('main.Sort By') :</span> @lang('main.Recent')
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">@lang('main.Recent')</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">@lang('main.Oldest')</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-nowrap datatable" id="complaintsTable">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('admin.image')</th>
                        <th>@lang('admin.mobile_number')</th>
                        <th>@lang('admin.email')</th>
                        <th>@lang('admin.message')</th>
                        <th>@lang('main.receiver')</th>
                        <th>@lang('admin.date')</th>
                        <th>@lang('admin.reply')</th>
                        <th>@lang('admin.action')</th>
                        <th style="display:none;">row_type</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($complains_box as $index => $complain_box)
                        @php
                            $rowType = empty($complain_box->clinic_id) ? 'user' : 'clinic';

                            if (!empty($complain_box->clinic_id)) {
                                $sender_type_to = __('main.clinic');
                                $receiver = $complain_box->clinics->name ?? '';
                            } else {
                                $sender_type_to = __('main.admin');
                                $receiver = __('main.admin');
                            }
                        @endphp

                        <tr id="row-{{ $complain_box->id }}" class="complaint-row" data-type="{{ $rowType }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar avatar-sm me-2">
                                        <img src="{{ $complain_box->users->image ?? $complain_box->clinics->image ?? asset('assets/img/profiles/avatar-01.jpg') }}"
                                             alt="profile"
                                             class="rounded-circle">
                                    </a>
                                    <a href="javascript:void(0);" class="fw-medium">
                                        {{ $complain_box->users->name ?? $complain_box->clinics->name ?? '-' }}
                                    </a>
                                </div>
                            </td>
                            <td>{{ $complain_box->users->phone ?? $complain_box->clinics->phone ?? '-' }}</td>
                            <td>{{ $complain_box->users->email ?? $complain_box->clinics->email ?? '-' }}</td>
                            <td><p class="truncate-text mb-0">{{ $complain_box->complain ?? null }}</p></td>
                            <td>{{ $sender_type_to ?? null }}{{ $receiver ? ' , '.$receiver : '' }}</td>
                            <td>{{ optional($complain_box->created_at)->format('d M Y H:i:s a') }}</td>
                            <td>{{ $complain_box->reply ?? null }}</td>
                            <td>
                                <a href="javascript:void(0);" class="link-reset fs-18 p-1" data-bs-toggle="modal" data-bs-target="#edit_complain_{{ $complain_box->id }}">
                                    <i class="ti ti-pencil"></i>
                                </a>
                                <a href="javascript:void(0);"
                                   class="link-reset fs-18 p-1 delete-btn"
                                   data-route="{{ route('delete-message', $complain_box->id) }}"
                                   data-id="row-{{ $complain_box->id }}"
                                   data-bs-toggle="modal"
                                   data-bs-target="#genericDeleteModal">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </td>
                            <td style="display:none;">{{ $rowType }}</td>
                        </tr>

                        <div id="edit_complain_{{ $complain_box->id }}" class="modal fade">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="text-dark modal-title fw-bold">
                                            @lang('admin.reply_message')
                                            {{ $complain_box->users->name ?? $complain_box->clinics->name ?? '-' }}
                                        </h4>
                                        <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>

                                    <form id="add_complain_form_{{ $complain_box->id }}" action="{{ route('add-reply', $complain_box->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-2">
                                                        <label class="form-label">
                                                            {{ empty($complain_box->clinic_id) ? trans('main.Patient') : trans('main.clinic') }}
                                                            <span class="text-danger ms-1">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" readonly
                                                               value="{{ $complain_box->users->name ?? $complain_box->clinics->name ?? '-' }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="mb-2">
                                                        <label class="form-label">@lang('admin.message')</label>
                                                        <textarea class="form-control" readonly rows="3">{{ $complain_box->complain }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="mb-2">
                                                        <label class="form-label">@lang('admin.reply_message')</label>
                                                        <textarea class="form-control"
                                                                  name="message"
                                                                  placeholder="@lang('admin.enter_text_here')"
                                                                  required
                                                                  rows="3">{{ $complain_box->reply ?? null }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex align-items-center gap-1">
                                            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">{{ trans('admin.cancel') }}</button>
                                            <button type="submit" class="btn btn-primary">{{ trans('admin.Save Changes') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="complaints-empty-state" id="complaintsEmptyState">
                @lang('main.No data found')
            </div>

            {{ $complains_box->links() }}
        </div>

        @component('components.footer')
        @endcomponent
        @include('layout_new.partials.delete_modal')
    </div>

@endsection


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.complaints-tab-btn');
            const emptyState = document.getElementById('complaintsEmptyState');
            const tableElement = document.getElementById('complaintsTable');

            let dataTableInstance = null;

            function getDataTableInstance() {
                if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#complaintsTable')) {
                    return $('#complaintsTable').DataTable();
                }
                return null;
            }

            function filterNormalTable(type) {
                const rows = tableElement.querySelectorAll('tbody tr');
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowType = row.getAttribute('data-type');

                    if (type === 'all' || rowType === type) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptyState) {
                    emptyState.style.display = visibleCount ? 'none' : 'block';
                }
            }

            function filterDataTable(type) {
                if (!dataTableInstance) return;

                if (type === 'all') {
                    dataTableInstance.column(8).search('').draw();
                } else {
                    dataTableInstance.column(8).search('^' + type + '$', true, false).draw();
                }

                setTimeout(() => {
                    const count = dataTableInstance.rows({ filter: 'applied' }).count();
                    if (emptyState) {
                        emptyState.style.display = count ? 'none' : 'block';
                    }
                }, 50);
            }

            function applyTabFilter(type) {
                dataTableInstance = getDataTableInstance();

                if (dataTableInstance) {
                    filterDataTable(type);
                } else {
                    filterNormalTable(type);
                }
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    applyTabFilter(this.getAttribute('data-tab'));
                });
            });

            setTimeout(() => {
                dataTableInstance = getDataTableInstance();

                if (dataTableInstance) {
                    dataTableInstance.on('draw', function () {
                        const count = dataTableInstance.rows({ filter: 'applied' }).count();
                        if (emptyState) {
                            emptyState.style.display = count ? 'none' : 'block';
                        }
                    });
                }

                applyTabFilter('all');
            }, 300);
        });
    </script>

