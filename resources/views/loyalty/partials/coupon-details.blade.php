@php
    $locale = app()->getLocale();
    $exchange = $exchange ?? null;
    $transactions = $coupon->spendTransactions()->get();
    $pointsCost = $coupon->pointsCost($exchange);
    $remaining = $coupon->remainingUsage();
@endphp

<div class="row">
    <div class="col-md-6">
        <h6 class="text-muted mb-2">@lang('main.loyalty_coupon_details')</h6>
        <table class="table table-sm table-bordered mb-0">
            <tr>
                <th>@lang('main.loyalty_service_name')</th>
                <td>{{ $locale === 'ar' ? $coupon->service_name_ar : ($coupon->service_name_en ?: $coupon->service_name_ar) }}</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_details_ar')</th>
                <td>{{ $coupon->details_ar ?: '—' }}</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_details_en')</th>
                <td>{{ $coupon->details_en ?: '—' }}</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_price_before_discount')</th>
                <td>{{ number_format((float) $coupon->price_before_discount, 2) }} @lang('main.loyalty_currency_sar')</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_discount_type')</th>
                <td>{{ $coupon->discount_type === 'fixed' ? __('main.loyalty_discount_fixed') : __('main.loyalty_discount_percentage') }}</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_discount_value')</th>
                <td>
                    {{ $coupon->discount_value }}
                    {{ $coupon->discount_type === 'fixed' ? __('main.loyalty_currency_sar') : '%' }}
                </td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_price_after_discount')</th>
                <td>{{ number_format((float) $coupon->price_after_discount, 2) }} @lang('main.loyalty_currency_sar')</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_discount_cost')</th>
                <td>{{ number_format($coupon->discountCost(), 2) }} @lang('main.loyalty_currency_sar')</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_points_cost')</th>
                <td>{{ $pointsCost }} @lang('admin.points')</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_expires_at')</th>
                <td>{{ $coupon->expires_at?->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th>@lang('admin.status')</th>
                <td>{{ $coupon->status ? __('main.enabled') : __('main.disabled') }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted mb-2">@lang('main.loyalty_usage_summary')</h6>
        <table class="table table-sm table-bordered mb-3">
            <tr>
                <th>@lang('main.loyalty_usage_limit')</th>
                <td>{{ $coupon->usage_limit > 0 ? $coupon->usage_limit : __('main.unlimited') }}</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_usage_count')</th>
                <td>{{ $coupon->usage_count ?? $coupon->usageCount() }}</td>
            </tr>
            <tr>
                <th>@lang('main.loyalty_usage_remaining')</th>
                <td>
                    @if($remaining === null)
                        @lang('main.unlimited')
                    @else
                        {{ $remaining }}
                    @endif
                </td>
            </tr>
        </table>

        <h6 class="text-muted mb-2">@lang('main.loyalty_redemption_history')</h6>
        <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
            <table class="table table-sm table-striped mb-0">
                <thead>
                <tr>
                    <th>@lang('main.users')</th>
                    <th>@lang('main.loyalty_code')</th>
                    <th>@lang('admin.points')</th>
                    <th>@lang('admin.status')</th>
                    <th>@lang('admin.date')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($coupon->redemptions as $redemption)
                    <tr>
                        <td>{{ optional($redemption->user)->name }}</td>
                        <td><code>{{ $redemption->code }}</code></td>
                        <td>{{ $redemption->points_spent }}</td>
                        <td>{{ $redemption->status }}</td>
                        <td>{{ $redemption->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">@lang('main.no_data')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<h6 class="text-muted mb-2 mt-3">@lang('main.loyalty_related_transactions')</h6>
<div class="table-responsive">
    <table class="table table-sm table-striped mb-0">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('main.users')</th>
            <th>@lang('admin.points')</th>
            <th>@lang('admin.details_ar')</th>
            <th>@lang('admin.date')</th>
        </tr>
        </thead>
        <tbody>
        @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ optional($transaction->user)->name }}</td>
                <td>{{ $transaction->points }}</td>
                <td>{{ $locale === 'ar' ? $transaction->description_ar : $transaction->description_en }}</td>
                <td>{{ $transaction->created_at?->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">@lang('main.no_data')</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
