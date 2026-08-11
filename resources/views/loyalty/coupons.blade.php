@extends('includes_admin.mainlayout')

@section('content')

    <div class="page-body">

        <div class="container-fluid">

            <div class="page-header">

                <div class="row">

                    <div class="col">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>

                            <li class="breadcrumb-item active">@lang('main.loyalty_coupons')</li>

                        </ol>

                    </div>

                </div>

            </div>

        </div>



        <div class="container-fluid">

            @include('loyalty.partials.nav')



            <div class="card">

                <div class="card-body">

                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addCouponModal">

                        @lang('main.loyalty_add_coupon')

                    </button>



                    <div class="table-responsive">

                        <table class="table table-striped">

                            <thead>

                            <tr>

                                <th>#</th>

                                <th>@lang('main.loyalty_service_name')</th>

                                <th>@lang('main.loyalty_price_before_discount')</th>

                                <th>@lang('main.loyalty_discount_type')</th>

                                <th>@lang('main.loyalty_discount_value')</th>

                                <th>@lang('main.loyalty_price_after_discount')</th>

                                <th>@lang('main.loyalty_usage_limit')</th>

                                <th>@lang('main.loyalty_expires_at')</th>

                                <th>@lang('admin.status')</th>

                                <th>@lang('admin.Actions')</th>

                            </tr>

                            </thead>

                            <tbody>

                            @forelse($data['coupons'] as $coupon)

                                <tr>

                                    <td>{{ $coupon->id }}</td>

                                    <td>{{ app()->getLocale() === 'ar' ? $coupon->service_name_ar : ($coupon->service_name_en ?: $coupon->service_name_ar) }}</td>

                                    <td>{{ number_format((float) $coupon->price_before_discount, 2) }} @lang('main.loyalty_currency_sar')</td>

                                    <td>

                                        {{ $coupon->discount_type === 'fixed'

                                            ? __('main.loyalty_discount_fixed')

                                            : __('main.loyalty_discount_percentage') }}

                                    </td>

                                    <td>

                                        {{ $coupon->discount_value }}

                                        {{ $coupon->discount_type === 'fixed' ? __('main.loyalty_currency_sar') : '%' }}

                                    </td>

                                    <td>{{ number_format((float) $coupon->price_after_discount, 2) }} @lang('main.loyalty_currency_sar')</td>

                                    <td>{{ $coupon->usage_limit > 0 ? $coupon->usage_limit : __('main.unlimited') }}</td>

                                    <td>{{ $coupon->expires_at?->format('Y-m-d') }}</td>

                                    <td>
                                        <div class="form-check form-switch mb-0">
                                            <input type="checkbox" class="form-check-input coupon-status-toggle"
                                                   data-id="{{ $coupon->id }}"
                                                   {{ $coupon->status ? 'checked' : '' }}>
                                        </div>
                                    </td>

                                    <td>

                                        <button class="btn btn-sm btn-secondary" data-toggle="modal"
                                                data-target="#showCoupon{{ $coupon->id }}">@lang('admin.show')</button>

                                        <button class="btn btn-sm btn-info" data-toggle="modal"

                                                data-target="#editCoupon{{ $coupon->id }}">@lang('admin.edit')</button>

                                        <form action="{{ route('loyalty.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger"

                                                    onclick="return confirm(@json(__('admin.confirm_delete')))">@lang('admin.delete')</button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="10" class="text-center text-muted">@lang('main.no_data')</td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>



                    {{ $data['coupons']->links() }}

                </div>

            </div>

        </div>

    </div>



    @foreach($data['coupons'] as $coupon)

        <div class="modal fade" id="showCoupon{{ $coupon->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('admin.show') — {{ app()->getLocale() === 'ar' ? $coupon->service_name_ar : ($coupon->service_name_en ?: $coupon->service_name_ar) }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        @include('loyalty.partials.coupon-details', ['coupon' => $coupon, 'exchange' => $data['exchange'] ?? null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin.Close')</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editCoupon{{ $coupon->id }}" tabindex="-1">

            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <form method="POST" action="{{ route('loyalty.coupons.update', $coupon->id) }}" class="coupon-form">

                        @csrf

                        @method('PUT')

                        <div class="modal-header">

                            <h5 class="modal-title">@lang('admin.edit')</h5>

                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>

                        </div>

                        <div class="modal-body">

                            @include('loyalty.partials.coupon-form-fields', ['coupon' => $coupon])

                        </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">@lang('admin.save')</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach



    <div class="modal fade" id="addCouponModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <form method="POST" action="{{ route('loyalty.coupons.store') }}" class="coupon-form">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">@lang('main.loyalty_add_coupon')</h5>

                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>

                    </div>

                    <div class="modal-body">

                        @include('loyalty.partials.coupon-form-fields', ['coupon' => null])

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">@lang('admin.save')</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection



@section('scripts')

    <script>

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



        function initCouponForm(form) {

            updateDiscountSuffix(form.querySelector('.discount-type-select'));

            calculatePriceAfterDiscount(form);

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

        });



        document.querySelectorAll('.coupon-form').forEach(function (form) {

            initCouponForm(form);

        });



        document.querySelectorAll('.coupon-status-toggle').forEach(function (toggle) {

            toggle.addEventListener('change', function () {

                var id = this.dataset.id;

                var status = this.checked ? 1 : 0;

                var checkbox = this;

                fetch("{{ url('admin/loyalty/coupons') }}/" + id + "/status/" + status, {

                    headers: {

                        'X-Requested-With': 'XMLHttpRequest',

                        'Accept': 'application/json'

                    }

                }).catch(function () {

                    checkbox.checked = !checkbox.checked;

                });

            });

        });

    </script>

@endsection

