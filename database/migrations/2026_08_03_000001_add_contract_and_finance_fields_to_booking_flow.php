<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddContractAndFinanceFieldsToBookingFlow extends Migration
{
    public function up()
    {
        Schema::create('clinic_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->unique()->constrained('clinics')->cascadeOnDelete();
            $table->string('contract_model', 40)->default('cash_commission');
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('annual_subscription_amount', 10, 2)->nullable();
            $table->date('annual_subscription_starts_at')->nullable();
            $table->date('annual_subscription_ends_at')->nullable();
            $table->boolean('rendezvous_badge_enabled')->default(false);
            $table->timestamps();
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->string('booking_flow', 40)->default('instant')->after('waiting_list');
            $table->string('payment_method', 30)->default('cash')->after('booking_flow');
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->decimal('platform_commission_rate', 5, 2)->default(0)->after('payment_reference');
            $table->decimal('platform_commission_amount', 10, 2)->default(0)->after('platform_commission_rate');
            $table->decimal('clinic_net_amount', 10, 2)->default(0)->after('platform_commission_amount');
            $table->string('settlement_direction', 40)->nullable()->after('clinic_net_amount');
            $table->date('financial_cycle_date')->nullable()->after('settlement_direction');
        });

        DB::statement('ALTER TABLE reservations MODIFY `date` date NULL');
        DB::statement('ALTER TABLE reservations MODIFY `appointment` varchar(255) NULL');
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'booking_flow',
                'payment_method',
                'payment_reference',
                'platform_commission_rate',
                'platform_commission_amount',
                'clinic_net_amount',
                'settlement_direction',
                'financial_cycle_date',
            ]);
        });

        Schema::dropIfExists('clinic_contracts');
    }
}
