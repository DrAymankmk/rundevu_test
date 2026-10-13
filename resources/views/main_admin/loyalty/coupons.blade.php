<?php $page = 'loyalty-coupons'; ?>

@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">
                    @lang('main.loyalty_coupons')
                    <span class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">
                        {{ $coupons->total() }}
                    </span>
                </h4>
                <p class="text-muted mb-0 mt-1">@lang('main.loyalty_coupons_admin_hint')</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                    @lang('main.loyalty_add_coupon')
                </button>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('loyalty-coupons') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">@lang('main.clinics')</label>
                        <select name="clinic_id" class="form-control">
                            <option value="">@lang('main.all')</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}" {{ (string) request('clinic_id') === (string) $clinic->id ? 'selected' : '' }}>
                                    {{ $clinic->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('main.loyalty_show_on_mobile_home')</label>
                        <select name="show_on_mobile_home" class="form-control">
                            <option value="">@lang('main.all')</option>
                            <option value="1" {{ request('show_on_mobile_home') === '1' ? 'selected' : '' }}>@lang('main.loyalty_show_on_mobile_home_yes')</option>
                            <option value="0" {{ request('show_on_mobile_home') === '0' ? 'selected' : '' }}>@lang('main.loyalty_show_on_mobile_home_no')</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('admin.status')</label>
                        <select name="status" class="form-control">
                            <option value="">@lang('main.all')</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>@lang('main.enabled')</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>@lang('main.disabled')</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">@lang('admin.search')</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('main.clinics')</th>
                            <th>@lang('main.loyalty_service_name')</th>
                            <th>@lang('main.loyalty_coupon_branches')</th>
                            <th>@lang('main.loyalty_show_on_mobile_home')</th>
                            <th>@lang('main.loyalty_price_after_discount')</th>
                            <th>@lang('main.loyalty_expires_at')</th>
                            <th>@lang('admin.status')</th>
                            <th>@lang('admin.Actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($coupons as $coupon)
                            @php $locale = app()->getLocale(); @endphp
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td>{{ optional($coupon->clinic)->name }}</td>
                                <td>{{ $locale === 'ar' ? $coupon->service_name_ar : ($coupon->service_name_en ?: $coupon->service_name_ar) }}</td>
                                <td>
                                    @if($coupon->appliesToAllBranches())
                                        <span class="text-muted">@lang('main.loyalty_coupon_branches_all')</span>
                                    @else
                                        @foreach($coupon->applicableBranches() as $branch)
                                            @php
                                                $cityName = $locale === 'ar'
                                                    ? (optional($branch->city)->name_ar ?: optional($branch->city)->name_en)
                                                    : (optional($branch->city)->name_en ?: optional($branch->city)->name_ar);
                                            @endphp
                                            <div class="small">
                                                {{ $branch->name }}
                                                @if($cityName)
                                                    <span class="text-muted">({{ $cityName }})</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->show_on_mobile_home)
                                        <span class="badge bg-success">@lang('main.loyalty_show_on_mobile_home_yes')</span>
                                    @else
                                        <span class="badge bg-secondary">@lang('main.loyalty_show_on_mobile_home_no')</span>
                                    @endif
                                </td>
                                <td>{{ number_format((float) $coupon->price_after_discount, 2) }} @lang('main.loyalty_currency_sar')</td>
                                <td>{{ $coupon->expires_at?->format('Y-m-d') }}</td>
                                <td>
                                    <div class="form-check form-switch mb-0">
                                        <input type="checkbox" class="form-check-input coupon-status-toggle"
                                               data-id="{{ $coupon->id }}"
                                               {{ $coupon->status ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#showCoupon{{ $coupon->id }}">@lang('admin.show')</button>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#editCoupon{{ $coupon->id }}">@lang('admin.edit')</button>
                                    <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                            data-bs-target="#notificationShapeCoupon{{ $coupon->id }}">
                                        @lang('main.loyalty_coupon_notification_shape')
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary btn-open-coupon-patients"
                                            data-coupon-id="{{ $coupon->id }}">
                                        @lang('main.loyalty_coupon_patients')
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning btn-send-coupon-test"
                                            data-coupon-id="{{ $coupon->id }}">
                                        @lang('main.loyalty_coupon_send_test')
                                    </button>
                                    <form action="{{ route('loyalty-coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm(@json(__('admin.confirm_delete')))">@lang('admin.delete')</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">@lang('main.no_data')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $coupons->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($coupons as $coupon)
    <div class="modal fade" id="showCoupon{{ $coupon->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('admin.show')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('loyalty.partials.coupon-details', ['coupon' => $coupon, 'exchange' => $exchange ?? null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.Close')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notificationShapeCoupon{{ $coupon->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @lang('main.loyalty_coupon_notification_preview_title')
                        <span class="text-muted fs-6">#{{ $coupon->id }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('main_admin.loyalty.partials.coupon-notification-preview', [
                        'coupon' => $coupon,
                        'isTest' => false,
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.Close')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCoupon{{ $coupon->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('loyalty-coupons.update', $coupon->id) }}" class="coupon-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('admin.edit')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('main_admin.loyalty.partials.coupon-form-fields', [
                            'coupon' => $coupon,
                            'clinics' => $clinics,
                            'modalKey' => $coupon->id,
                        ])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.cancel')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="couponPatientsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.loyalty_coupon_patients')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="couponPatientsModalBody">
                <div class="text-center text-muted py-4">...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.Close')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCouponModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('loyalty-coupons.store') }}" class="coupon-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">@lang('main.loyalty_add_coupon')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('main_admin.loyalty.partials.coupon-form-fields', [
                        'coupon' => null,
                        'clinics' => $clinics,
                        'modalKey' => 'new',
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .loyalty-phone-frame {
        max-width: 320px;
        margin: 0 auto;
        background: linear-gradient(160deg, #1f2937 0%, #111827 100%);
        border-radius: 28px;
        padding: 28px 14px 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.25);
        min-height: 180px;
        position: relative;
    }
    .loyalty-phone-notch {
        width: 86px;
        height: 10px;
        border-radius: 999px;
        background: #0b1220;
        margin: 0 auto 16px;
    }
    .loyalty-push-banner {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 16px;
        padding: 12px 12px 10px;
        color: #111827;
    }
    .loyalty-push-app-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #0d6efd;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .loyalty-push-app-name,
    .loyalty-push-time {
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .02em;
    }
    .loyalty-push-title {
        font-size: 14px;
        font-weight: 700;
        line-height: 1.35;
        margin-top: 2px;
    }
    .loyalty-push-body {
        font-size: 13px;
        color: #374151;
        line-height: 1.4;
        margin-top: 2px;
        word-break: break-word;
    }
</style>
@endpush

@push('scripts')
<script>
    window.loyaltyBranchesByClinic = @json($branchesByClinic ?? []);
    window.loyaltyBranchesUrl = @json(url('admin/loyalty-coupons/branches'));

    function updateDiscountSuffix(select) {
        var form = select.closest('form');
        var type = select.value;
        var suffix = type === 'fixed' ? @json(__('main.loyalty_currency_sar')) : '%';
        var suffixEl = form.querySelector('.discount-value-suffix');
        if (suffixEl) {
            suffixEl.textContent = suffix;
        }
    }

    function calculatePriceAfterDiscount(form) {
        var before = parseFloat(form.querySelector('.price-before-discount')?.value) || 0;
        var type = form.querySelector('.discount-type-select')?.value || 'percentage';
        var discount = parseFloat(form.querySelector('.discount-value-input')?.value) || 0;
        var after = before;

        if (type === 'percentage') {
            after = before - (before * discount / 100);
        } else {
            after = before - discount;
        }

        after = Math.max(0, Math.round(after * 100) / 100);
        var afterEl = form.querySelector('.price-after-discount');
        if (afterEl) {
            afterEl.value = after.toFixed(2);
        }
    }

    function fillBranchesSelect(form, clinicId, selectedIds) {
        var select = form.querySelector('.coupon-branches-select');
        var emptyHint = form.querySelector('.coupon-branches-empty');
        if (!select) {
            return;
        }

        selectedIds = (selectedIds || []).map(function (id) { return parseInt(id, 10); });
        var branches = window.loyaltyBranchesByClinic[clinicId] || [];

        select.innerHTML = '';
        branches.forEach(function (branch) {
            var option = document.createElement('option');
            option.value = branch.id;
            option.textContent = branch.label;
            if (selectedIds.indexOf(parseInt(branch.id, 10)) !== -1) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        if (emptyHint) {
            emptyHint.classList.toggle('d-none', branches.length > 0 || !clinicId);
        }

        if (window.jQuery && jQuery.fn.select2) {
            var $select = jQuery(select);
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }
            $select.select2({
                width: '100%',
                placeholder: @json(__('main.loyalty_coupon_branches')),
                allowClear: true,
                dropdownParent: $select.closest('.modal')
            });
        }
    }

    function initCouponForm(form) {
        var clinicSelect = form.querySelector('.coupon-clinic-select');
        var branchSelect = form.querySelector('.coupon-branches-select');
        var selected = branchSelect ? JSON.parse(branchSelect.getAttribute('data-selected') || '[]') : [];
        var clinicId = clinicSelect ? clinicSelect.value : '';

        updateDiscountSuffix(form.querySelector('.discount-type-select'));
        calculatePriceAfterDiscount(form);
        fillBranchesSelect(form, clinicId, selected);
    }

    document.addEventListener('input', function (event) {
        if (event.target && event.target.classList.contains('coupon-price-input')) {
            var form = event.target.closest('form');
            if (form) {
                calculatePriceAfterDiscount(form);
            }
        }
    });

    document.addEventListener('change', function (event) {
        if (event.target && event.target.classList.contains('discount-type-select')) {
            updateDiscountSuffix(event.target);
            var form = event.target.closest('form');
            if (form) {
                calculatePriceAfterDiscount(form);
            }
        }

        if (event.target && event.target.classList.contains('coupon-clinic-select')) {
            var form = event.target.closest('form');
            if (form) {
                fillBranchesSelect(form, event.target.value, []);
            }
        }
    });

    document.querySelectorAll('.coupon-form').forEach(function (form) {
        initCouponForm(form);
    });

    document.querySelectorAll('.modal').forEach(function (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            var form = modal.querySelector('.coupon-form');
            if (form) {
                initCouponForm(form);
            }
        });
    });

    document.querySelectorAll('.coupon-status-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            var id = this.dataset.id;
            var status = this.checked ? 1 : 0;
            var checkbox = this;

            fetch("{{ url('admin/loyalty-coupons') }}/" + id + "/status/" + status, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).catch(function () {
                checkbox.checked = !checkbox.checked;
            });
        });
    });

    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || @json(csrf_token());
    var patientsModalEl = document.getElementById('couponPatientsModal');
    var patientsModalBody = document.getElementById('couponPatientsModalBody');
    var activePatientsCouponId = null;

    function jsonHeaders() {
        return {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        };
    }

    function loadCouponPatients(couponId, pageUrl, search) {
        activePatientsCouponId = couponId;
        patientsModalBody.innerHTML = '<div class="text-center text-muted py-4">...</div>';

        var url = pageUrl;
        if (!url) {
            url = "{{ url('admin/loyalty-coupons') }}/" + couponId + "/patients";
            if (typeof search === 'string' && search !== '') {
                url += (url.indexOf('?') === -1 ? '?' : '&') + 'search=' + encodeURIComponent(search);
            }
        }

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
            .then(function (response) { return response.text(); })
            .then(function (html) {
                patientsModalBody.innerHTML = html;
            })
            .catch(function () {
                patientsModalBody.innerHTML = '<div class="alert alert-danger mb-0">@lang("messages.something_went_wrong")</div>';
            });
    }

    function currentPatientsSearch() {
        var input = patientsModalBody.querySelector('.coupon-patients-search-form input[name="search"]');
        return input ? input.value.trim() : '';
    }

    document.querySelectorAll('.btn-open-coupon-patients').forEach(function (button) {
        button.addEventListener('click', function () {
            var couponId = this.dataset.couponId;
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(patientsModalEl).show();
            } else if (window.jQuery) {
                jQuery(patientsModalEl).modal('show');
            }
            loadCouponPatients(couponId);
        });
    });

    patientsModalBody.addEventListener('submit', function (event) {
        var searchForm = event.target.closest('.coupon-patients-search-form');
        if (!searchForm) {
            return;
        }
        event.preventDefault();
        var couponId = searchForm.dataset.couponId || activePatientsCouponId;
        var searchInput = searchForm.querySelector('input[name="search"]');
        loadCouponPatients(couponId, null, searchInput ? searchInput.value.trim() : '');
    });

    patientsModalBody.addEventListener('click', function (event) {
        var clearSearchBtn = event.target.closest('.btn-clear-coupon-patients-search');
        if (clearSearchBtn) {
            event.preventDefault();
            loadCouponPatients(clearSearchBtn.dataset.couponId || activePatientsCouponId, null, '');
            return;
        }

        var pageLink = event.target.closest('.coupon-patients-pagination a');
        if (pageLink) {
            event.preventDefault();
            loadCouponPatients(activePatientsCouponId, pageLink.getAttribute('href'));
            return;
        }

        var sendBtn = event.target.closest('.btn-send-coupon-notification');
        if (sendBtn) {
            event.preventDefault();
            var couponId = sendBtn.dataset.couponId;
            var userId = sendBtn.dataset.userId;
            var search = currentPatientsSearch();
            sendBtn.disabled = true;

            fetch("{{ url('admin/loyalty-coupons') }}/" + couponId + "/notify/" + userId, {
                method: 'POST',
                headers: jsonHeaders()
            })
                .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
                .then(function (result) {
                    alert(result.data.message || '');
                    if (result.ok) {
                        loadCouponPatients(couponId, null, search);
                    }
                })
                .catch(function () {
                    alert(@json(__('messages.something_went_wrong')));
                })
                .finally(function () {
                    sendBtn.disabled = false;
                });
            return;
        }

        var sendAllBtn = event.target.closest('.btn-send-all-coupon-notifications');
        if (sendAllBtn) {
            event.preventDefault();
            if (!confirm(@json(__('main.loyalty_coupon_send_all')))) {
                return;
            }
            var allCouponId = sendAllBtn.dataset.couponId;
            var allSearch = currentPatientsSearch();
            sendAllBtn.disabled = true;

            fetch("{{ url('admin/loyalty-coupons') }}/" + allCouponId + "/notify-all", {
                method: 'POST',
                headers: jsonHeaders()
            })
                .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
                .then(function (result) {
                    alert(result.data.message || '');
                    if (result.ok) {
                        loadCouponPatients(allCouponId, null, allSearch);
                    }
                })
                .catch(function () {
                    alert(@json(__('messages.something_went_wrong')));
                })
                .finally(function () {
                    sendAllBtn.disabled = false;
                });
        }
    });

    document.querySelectorAll('.btn-send-coupon-test').forEach(function (button) {
        button.addEventListener('click', function () {
            var couponId = this.dataset.couponId;
            var btn = this;
            if (!confirm(@json(__('main.loyalty_coupon_send_test')))) {
                return;
            }
            btn.disabled = true;

            fetch("{{ url('admin/loyalty-coupons') }}/" + couponId + "/notify-test", {
                method: 'POST',
                headers: jsonHeaders()
            })
                .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
                .then(function (result) {
                    alert(result.data.message || '');
                })
                .catch(function () {
                    alert(@json(__('messages.something_went_wrong')));
                })
                .finally(function () {
                    btn.disabled = false;
                });
        });
    });
</script>
@endpush
