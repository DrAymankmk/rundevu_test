<?php

namespace App\Http\Controllers\API;

use App\Models\Clinic;
use App\Models\ClinicContract;
use App\Repositories\App\MainRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClinicContractsController extends APIController
{
    public $repository;

    public function __construct(Request $request, MainRepository $repository)
    {
        $this->setLang($request->header('lang'));
        $this->repository = $repository;
    }

    public function show(Request $request)
    {
        $account = $this->repository->checkJwtAuth($request);
        if (!$account) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $clinic = $this->resolveClinicForAccount($account, $request->clinic_id);
        if (!$clinic) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        return $this->success('Clinic contract', $this->contractPayload($clinic));
    }

    public function update(Request $request)
    {
        $account = $this->repository->checkJwtAuth($request);
        if (!$account) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $clinic = $this->resolveClinicForAccount($account, $request->clinic_id);
        if (!$clinic) {
            return $this->respondForbidden(trans('messages.auth.user_check'));
        }

        $validator = Validator::make($request->all(), [
            'contract_model' => ['required', Rule::in([
                ClinicContract::ANNUAL_SUBSCRIPTION,
                ClinicContract::ONLINE_PAYMENT_COMMISSION,
                ClinicContract::CASH_COMMISSION,
            ])],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'annual_subscription_amount' => ['nullable', 'numeric', 'min:0'],
            'annual_subscription_starts_at' => ['nullable', 'date_format:Y-m-d'],
            'annual_subscription_ends_at' => ['nullable', 'date_format:Y-m-d'],
            'rendezvous_badge_enabled' => ['nullable', 'boolean'],
            'payment_method' => ['nullable', Rule::in([
                ClinicContract::PAYMENT_CASH,
                ClinicContract::PAYMENT_ONLINE,
            ])],
        ]);

        if ($validator->fails()) {
            return $this->throwValidation($validator->errors()->first());
        }

        $contractModel = $request->contract_model;
        $contract = ClinicContract::updateOrCreate(
            ['clinic_id' => $clinic->id],
            [
                'contract_model' => $contractModel,
                'commission_rate' => $contractModel === ClinicContract::ANNUAL_SUBSCRIPTION ? 0 : (float) ($request->commission_rate ?? 0),
                'annual_subscription_amount' => $request->annual_subscription_amount,
                'annual_subscription_starts_at' => $request->annual_subscription_starts_at,
                'annual_subscription_ends_at' => $request->annual_subscription_ends_at,
                'rendezvous_badge_enabled' => (bool) $request->rendezvous_badge_enabled,
                'payment_method' => $request->input('payment_method', ClinicContract::PAYMENT_CASH),
            ]
        );

        $clinic->setRelation('contract', $contract);

        return $this->success('Clinic contract updated', $this->contractPayload($clinic));
    }

    private function resolveClinicForAccount(Clinic $account, $requestedClinicId)
    {
        $clinicId = $requestedClinicId ?: $account->organizationClinicId();
        $clinic = Clinic::find($clinicId);

        if (!$clinic) {
            return null;
        }

        $clinic = Clinic::find($clinic->organizationClinicId());

        if ((int) $account->app_type === 6) {
            return $clinic;
        }

        return (int) $account->organizationClinicId() === (int) $clinic->organizationClinicId() ? $clinic : null;
    }

    private function contractPayload(Clinic $clinic): array
    {
        return [
            'clinic_id' => $clinic->id,
            'contract_model' => $this->contractValue($clinic, 'contract_model'),
            'commission_rate' => (float) $this->contractValue($clinic, 'commission_rate'),
            'annual_subscription_amount' => $this->contractValue($clinic, 'annual_subscription_amount'),
            'annual_subscription_starts_at' => optional($this->contractValue($clinic, 'annual_subscription_starts_at'))->format('Y-m-d'),
            'annual_subscription_ends_at' => optional($this->contractValue($clinic, 'annual_subscription_ends_at'))->format('Y-m-d'),
            'rendezvous_badge_enabled' => (bool) $this->contractValue($clinic, 'rendezvous_badge_enabled'),
            'payment_method' => $this->contractValue($clinic, 'payment_method'),
            'booking_policy' => $clinic->bookingPolicy(),
        ];
    }

    private function contractValue(Clinic $clinic, string $key)
    {
        $contract = $clinic->contract ?: new ClinicContract(ClinicContract::defaultAttributes());

        return $contract->{$key};
    }
}
