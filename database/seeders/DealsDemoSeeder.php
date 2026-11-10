<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\ClinicContract;
use App\Models\ClinicSpecialist;
use App\Models\Deal;
use App\Models\PatientDeal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DealsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $clinicId = 1;

        $clinic = Clinic::find($clinicId);
        if (!$clinic) {
            $this->command?->error("Clinic #{$clinicId} not found.");
            return;
        }

        $contract = ClinicContract::firstOrCreate(
            ['clinic_id' => $clinicId],
            ClinicContract::defaultAttributes()
        );

        $contract->update([
            'deal_revenue_type' => ClinicContract::DEAL_REVENUE_PERCENTAGE,
            'deal_revenue_amount' => 20,
        ]);

        $specialtyId = ClinicSpecialist::where('clinic_id', $clinicId)
            ->where('type', 1)
            ->where('status', 1)
            ->value('specialty_id');

        $patients = User::query()->orderBy('id')->limit(3)->get();
        if ($patients->isEmpty()) {
            $this->command?->error('No patients found in users table.');
            return;
        }

        $dealsData = [
            [
                'title_ar' => 'فحص شامل بخصم',
                'title_en' => 'Full checkup discount',
                'description_ar' => 'عرض تجريبي لفحص شامل في العيادة.',
                'description_en' => 'Demo deal for a full clinic checkup.',
                'conditions_ar' => 'صالح لمرة واحدة لكل مريض. غير قابل للاسترداد نقداً.',
                'conditions_en' => 'One use per patient. Non-refundable for cash.',
                'price_before' => 500,
                'price_after' => 350,
                'status' => Deal::STATUS_ACTIVE,
                'maximum_number_of_usage' => 50,
                'number_of_uses' => 0,
                'expiration_date' => now()->addMonths(2)->toDateString(),
            ],
            [
                'title_ar' => 'جلسة ليزر تجميلية',
                'title_en' => 'Cosmetic laser session',
                'description_ar' => 'جلسة ليزر تجريبية لاختبار الاسترداد والتسوية.',
                'description_en' => 'Demo laser session for redemption and settlement testing.',
                'conditions_ar' => 'يجب إبراز الكود عند الاستقبال.',
                'conditions_en' => 'Show the code at reception.',
                'price_before' => 800,
                'price_after' => 600,
                'status' => Deal::STATUS_ACTIVE,
                'maximum_number_of_usage' => 20,
                'number_of_uses' => 0,
                'expiration_date' => now()->addMonth()->toDateString(),
            ],
            [
                'title_ar' => 'عرض أسنان غير نشط',
                'title_en' => 'Inactive dental deal',
                'description_ar' => 'عرض غير نشط للاختبار فقط.',
                'description_en' => 'Inactive deal for testing only.',
                'conditions_ar' => 'غير متاح حالياً.',
                'conditions_en' => 'Not available currently.',
                'price_before' => 300,
                'price_after' => 199,
                'status' => Deal::STATUS_NOT_ACTIVE,
                'maximum_number_of_usage' => null,
                'number_of_uses' => 0,
                'expiration_date' => now()->addMonths(3)->toDateString(),
            ],
        ];

        $createdDeals = collect();

        foreach ($dealsData as $data) {
            $deal = Deal::updateOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'title_en' => $data['title_en'],
                ],
                array_merge($data, [
                    'clinic_id' => $clinicId,
                    'specialty_id' => $specialtyId,
                ])
            );
            $createdDeals->push($deal);
        }

        $activeDeals = $createdDeals->where('status', Deal::STATUS_ACTIVE)->values();
        $checkup = $activeDeals->first();
        $laser = $activeDeals->get(1) ?? $checkup;

        // Reset demo patient deals for these seeded deals (safe re-run)
        PatientDeal::whereIn('deal_id', $createdDeals->pluck('id'))->delete();

        foreach ($activeDeals as $deal) {
            $deal->update(['number_of_uses' => 0]);
        }

        $claims = [
            // unused — ready to confirm in Manage Deals
            [
                'deal' => $checkup,
                'patient' => $patients[0],
                'used' => false,
                'settlement_status' => PatientDeal::SETTLEMENT_PENDING,
            ],
            // used + pending settlement
            [
                'deal' => $checkup,
                'patient' => $patients->get(1) ?? $patients[0],
                'used' => true,
                'settlement_status' => PatientDeal::SETTLEMENT_PENDING,
            ],
            // used + transfer completed
            [
                'deal' => $laser,
                'patient' => $patients->get(2) ?? $patients[0],
                'used' => true,
                'settlement_status' => PatientDeal::SETTLEMENT_TRANSFER_COMPLETED,
            ],
            // second unused on laser for another patient
            [
                'deal' => $laser,
                'patient' => $patients[0],
                'used' => false,
                'settlement_status' => PatientDeal::SETTLEMENT_PENDING,
            ],
        ];

        $usedCountByDeal = [];

        foreach ($claims as $claim) {
            /** @var Deal $deal */
            $deal = $claim['deal'];
            $snapshots = $this->snapshots($deal, $contract);

            $patientDeal = PatientDeal::create([
                'deal_id' => $deal->id,
                'patient_id' => $claim['patient']->id,
                'generated_code' => $this->uniqueCode(),
                'used' => $claim['used'],
                'used_date_time' => $claim['used'] ? now()->subDays(rand(1, 5)) : null,
                'used_by' => $claim['used'] ? $clinicId : null,
                'price_after' => $snapshots['price_after'],
                'platform_entitlement' => $snapshots['platform_entitlement'],
                'clinic_net' => $snapshots['clinic_net'],
                'deal_revenue_type' => $snapshots['deal_revenue_type'],
                'deal_revenue_amount' => $snapshots['deal_revenue_amount'],
                'settlement_status' => $claim['settlement_status'],
                'settled_at' => $claim['settlement_status'] === PatientDeal::SETTLEMENT_TRANSFER_COMPLETED
                    ? now()->subDay()
                    : null,
            ]);

            if ($claim['used']) {
                $usedCountByDeal[$deal->id] = ($usedCountByDeal[$deal->id] ?? 0) + 1;
            }

            $this->command?->info("Patient deal #{$patientDeal->id} code={$patientDeal->generated_code} used=" . ($claim['used'] ? 'yes' : 'no'));
        }

        foreach ($usedCountByDeal as $dealId => $count) {
            Deal::whereKey($dealId)->update(['number_of_uses' => $count]);
        }

        $this->command?->info("Deals demo seeded for clinic #{$clinicId} ({$clinic->name}).");
        $this->command?->info('Contract deal revenue: percentage 20%.');
        $this->command?->info('Deals: ' . $createdDeals->count() . ' | Patient claims: ' . count($claims));
    }

    protected function snapshots(Deal $deal, ClinicContract $contract): array
    {
        $type = $contract->deal_revenue_type;
        $amount = (float) $contract->deal_revenue_amount;
        $priceAfter = (float) $deal->price_after;

        $platform = 0.0;
        if ($type === ClinicContract::DEAL_REVENUE_PERCENTAGE) {
            $platform = round($priceAfter * ($amount / 100), 2);
        } elseif ($type === ClinicContract::DEAL_REVENUE_FIXED) {
            $platform = round($amount, 2);
        }

        return [
            'price_after' => $priceAfter,
            'platform_entitlement' => $platform,
            'clinic_net' => round(max($priceAfter - $platform, 0), 2),
            'deal_revenue_type' => $type,
            'deal_revenue_amount' => $amount,
        ];
    }

    protected function uniqueCode(): string
    {
        do {
            $code = 'DL-' . Str::upper(Str::random(8));
        } while (PatientDeal::where('generated_code', $code)->exists());

        return $code;
    }
}
