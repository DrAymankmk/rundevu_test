@php
    $locale = $locale ?? app()->getLocale();
    $isTest = (bool) ($isTest ?? false);
    $compact = (bool) ($compact ?? false);

    $titleAr = $isTest ? 'اختبار كوبون مكافأة' : 'كوبون مكافأة جديد';
    $titleEn = $isTest ? 'Reward coupon test' : 'New reward coupon';
    $serviceAr = $coupon->service_name_ar;
    $serviceEn = $coupon->service_name_en ?: $coupon->service_name_ar;
    $messageAr = trim($serviceAr . ($coupon->details_ar ? ' — ' . $coupon->details_ar : ''));
    $messageEn = trim($serviceEn . ($coupon->details_en ? ' — ' . $coupon->details_en : ''));

    $previewTitle = $locale === 'ar' ? $titleAr : $titleEn;
    $previewMessage = $locale === 'ar' ? $messageAr : $messageEn;
@endphp

<div class="loyalty-notification-preview {{ $compact ? 'loyalty-notification-preview--compact' : '' }}">
    @unless($compact)
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">@lang('main.loyalty_coupon_notification_preview_title')</h6>
            <small class="text-muted">@lang('main.loyalty_coupon_notification_preview_hint')</small>
        </div>
    @endunless

    <div class="row g-3">
        <div class="col-lg-{{ $compact ? '12' : '5' }}">
            <div class="loyalty-phone-frame">
                <div class="loyalty-phone-notch"></div>
                <div class="loyalty-push-banner">
                    <div class="d-flex align-items-start gap-2">
                        <div class="loyalty-push-app-icon">R</div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <span class="loyalty-push-app-name">@lang('main.loyalty_coupon_notification_app_name')</span>
                                <span class="loyalty-push-time">@lang('main.loyalty_coupon_notification_now')</span>
                            </div>
                            <div class="loyalty-push-title">{{ $previewTitle }}</div>
                            <div class="loyalty-push-body">{{ $previewMessage }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @unless($compact)
            <div class="col-lg-7">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                        <tr>
                            <th style="width: 28%;">@lang('main.loyalty_coupon_notification_field')</th>
                            <th>@lang('main.loyalty_coupon_notification_ar')</th>
                            <th>@lang('main.loyalty_coupon_notification_en')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>@lang('main.loyalty_coupon_notification_title_field')</th>
                            <td>{{ $titleAr }}</td>
                            <td>{{ $titleEn }}</td>
                        </tr>
                        <tr>
                            <th>@lang('main.loyalty_coupon_notification_message_field')</th>
                            <td>{{ $messageAr }}</td>
                            <td>{{ $messageEn }}</td>
                        </tr>
                        <tr>
                            <th>type / app_type</th>
                            <td colspan="2"><code>1</code> / <code>1</code></td>
                        </tr>
                        <tr>
                            <th>clinic_id</th>
                            <td colspan="2"><code>{{ $coupon->clinic_id }}</code></td>
                        </tr>
                        <tr>
                            <th>url</th>
                            <td colspan="2"><code>{{ $coupon->notificationUrl() }}</code></td>
                        </tr>
                        <tr>
                            <th>flag</th>
                            <td colspan="2"><code>{{ $coupon->id }}</code> (@lang('main.loyalty_coupon_notification_flag_hint'))</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endunless
    </div>
</div>
