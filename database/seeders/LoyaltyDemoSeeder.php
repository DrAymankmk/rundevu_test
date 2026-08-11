<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\LoyaltyCouponRedemption;
use App\Models\LoyaltyPointTransaction;
use App\Models\LoyaltyRewardCoupon;
use App\Models\User;
use App\Services\LoyaltyPointsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoyaltyDemoSeeder extends Seeder
{
    public function run()
    {
        $clinic = Clinic::firstOrCreate(
            ['email' => 'loyalty.clinic@test.local'],
            [
                'name' => 'عيادة اختبار النقاط',
                'phone' => '0500000001',
                'password' => Hash::make('123456'),
                'status' => 1,
                'app_type' => 1,
                'city_id' => 1,
                'jwt_token' => Str::random(75),
            ]
        );

        $clinic->update([
            'points_enabled' => 1,
            'points_category' => 'clinic',
        ]);

        $reception = Clinic::firstOrCreate(
            ['email' => 'loyalty.reception@test.local'],
            [
                'name' => 'استقبال اختبار النقاط',
                'phone' => '0500000002',
                'password' => Hash::make('123456'),
                'status' => 1,
                'app_type' => 2,
                'parent_id' => $clinic->id,
                'city_id' => 1,
                'jwt_token' => Str::random(75),
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'loyalty.patient@test.local'],
            [
                'name' => 'مريض اختبار النقاط',
                'phone' => '0500000003',
                'password' => Hash::make('123456'),
                'status' => 1,
                'city_id' => 1,
                'jwt_token' => Str::random(75),
                'expired_date' => now()->addYear()->toDateString(),
            ]
        );

        LoyaltyPointTransaction::updateOrCreate(
            [
                'user_id' => $user->id,
                'source_type' => 'loyalty_demo_seed',
                'source_id' => 1,
                'rule_key' => 'demo_balance',
            ],
            [
                'clinic_id' => $clinic->id,
                'type' => 'adjustment',
                'points' => 200,
                'description_ar' => 'رصيد تجريبي لاختبار دورة نظام النقاط',
                'description_en' => 'Demo balance for loyalty cycle testing',
                'expires_at' => now()->addMonths(12),
                'status' => 1,
            ]
        );

        $consultationCoupon = LoyaltyRewardCoupon::updateOrCreate(
            [
                'clinic_id' => $clinic->id,
                'service_name_ar' => 'كشف تجريبي بنظام النقاط',
            ],
            [
                'service_name_en' => 'Demo loyalty consultation',
                'details_ar' => 'كوبون خصم تجريبي لاختبار الاستبدال والتأكيد بالـ OTP',
                'details_en' => 'Demo coupon to test redemption and OTP confirmation',
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'points_required' => 80,
                'expires_at' => now()->addMonth()->toDateString(),
                'branch_ids' => [],
                'status' => 1,
            ]
        );

        LoyaltyRewardCoupon::updateOrCreate(
            [
                'clinic_id' => $clinic->id,
                'service_name_ar' => 'تحليل CBC تجريبي',
            ],
            [
                'service_name_en' => 'Demo CBC analysis',
                'details_ar' => 'كوبون ثاني للتأكد من ظهور متجر المكافآت',
                'details_en' => 'Second demo coupon for rewards store testing',
                'discount_type' => 'fixed',
                'discount_value' => 100,
                'points_required' => 50,
                'expires_at' => now()->addMonth()->toDateString(),
                'branch_ids' => [],
                'status' => 1,
            ]
        );

        $redemption = LoyaltyCouponRedemption::where('user_id', $user->id)
            ->where('coupon_id', $consultationCoupon->id)
            ->whereIn('status', ['pending', 'otp_sent'])
            ->first();

        if (!$redemption) {
            $redemption = app(LoyaltyPointsService::class)->redeem($user, $consultationCoupon);
        }

        $this->command->info('Loyalty demo data is ready.');
        $this->command->info('Clinic: ' . $clinic->email . ' / 123456');
        $this->command->info('Reception: ' . $reception->email . ' / 123456');
        $this->command->info('Patient: ' . $user->email . ' / 123456');
        $this->command->info('Redemption code: ' . $redemption->code);
        $this->command->info('Patient balance: ' . app(LoyaltyPointsService::class)->balance($user->id));
    }
}
