<div class="card border-primary mb-3">
    <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fa-solid fa-circle-info text-primary me-2"></i>
            @lang('main.loyalty_process_title')
        </h5>
        <button class="btn btn-sm btn-outline-primary" type="button"
                data-bs-toggle="collapse" data-bs-target="#loyaltyProcessGuide"
                aria-expanded="true" aria-controls="loyaltyProcessGuide">
            @lang('main.loyalty_process_toggle')
        </button>
    </div>
    <div id="loyaltyProcessGuide" class="collapse show">
        <div class="card-body">
            <p class="text-muted mb-4">@lang('main.loyalty_process_intro')</p>

            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="border rounded p-3 h-100">
                        <span class="badge bg-primary mb-2">1</span>
                        <h6 class="fw-bold">@lang('main.loyalty_process_step_earn_title')</h6>
                        <p class="small text-muted mb-0">@lang('main.loyalty_process_step_earn_desc')</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="border rounded p-3 h-100">
                        <span class="badge bg-primary mb-2">2</span>
                        <h6 class="fw-bold">@lang('main.loyalty_process_step_coupons_title')</h6>
                        <p class="small text-muted mb-2">@lang('main.loyalty_process_step_coupons_desc')</p>
                        <a href="{{ route('loyalty.coupons') }}" class="small">@lang('main.loyalty_coupons') &rarr;</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="border rounded p-3 h-100">
                        <span class="badge bg-primary mb-2">3</span>
                        <h6 class="fw-bold">@lang('main.loyalty_process_step_redeem_title')</h6>
                        <p class="small text-muted mb-0">@lang('main.loyalty_process_step_redeem_desc')</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="border rounded p-3 h-100">
                        <span class="badge bg-primary mb-2">4</span>
                        <h6 class="fw-bold">@lang('main.loyalty_process_step_confirm_title')</h6>
                        <p class="small text-muted mb-2">@lang('main.loyalty_process_step_confirm_desc')</p>
                        <a href="{{ route('loyalty.redemptions') }}" class="small">@lang('main.loyalty_redemptions') &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <h6 class="fw-bold mb-2">@lang('main.loyalty_process_flow_title')</h6>
                <p class="small text-muted mb-3">@lang('main.loyalty_process_flow_desc')</p>
                <div class="d-flex flex-wrap align-items-center gap-2 small loyalty-flow-chain">
                    <span class="badge bg-light text-dark border">@lang('main.loyalty_process_flow_patient_redeems')</span>
                    <i class="fa-solid fa-arrow-right text-muted"></i>
                    <span class="badge bg-light text-dark border">@lang('main.loyalty_process_flow_points_deducted')</span>
                    <i class="fa-solid fa-arrow-right text-muted"></i>
                    <span class="badge bg-light text-dark border">@lang('main.loyalty_process_flow_pending')</span>
                    <i class="fa-solid fa-arrow-right text-muted"></i>
                    <span class="badge bg-light text-dark border">@lang('main.loyalty_process_flow_otp_sent')</span>
                    <i class="fa-solid fa-arrow-right text-muted"></i>
                    <span class="badge bg-success">@lang('main.loyalty_process_flow_used')</span>
                </div>
            </div>

            <ul class="small text-muted mt-4 mb-0 ps-3">
                <li>@lang('main.loyalty_process_note_wallet')</li>
                <li>@lang('main.loyalty_process_note_org')</li>
                <li>@lang('main.loyalty_process_note_reception')</li>
            </ul>
        </div>
    </div>
</div>
