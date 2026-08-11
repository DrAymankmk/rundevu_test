<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoveClinicContractFieldsToContractsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('clinic_contracts')) {
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
        }

        if (Schema::hasColumn('clinics', 'contract_model')) {
            DB::statement("
                INSERT INTO clinic_contracts (
                    clinic_id,
                    contract_model,
                    commission_rate,
                    annual_subscription_amount,
                    annual_subscription_starts_at,
                    annual_subscription_ends_at,
                    rendezvous_badge_enabled,
                    created_at,
                    updated_at
                )
                SELECT
                    id,
                    COALESCE(contract_model, 'cash_commission'),
                    COALESCE(commission_rate, 0),
                    annual_subscription_amount,
                    annual_subscription_starts_at,
                    annual_subscription_ends_at,
                    COALESCE(rendezvous_badge_enabled, 0),
                    NOW(),
                    NOW()
                FROM clinics
                WHERE app_type IN (1, 4, 5, 7)
                ON DUPLICATE KEY UPDATE
                    contract_model = VALUES(contract_model),
                    commission_rate = VALUES(commission_rate),
                    annual_subscription_amount = VALUES(annual_subscription_amount),
                    annual_subscription_starts_at = VALUES(annual_subscription_starts_at),
                    annual_subscription_ends_at = VALUES(annual_subscription_ends_at),
                    rendezvous_badge_enabled = VALUES(rendezvous_badge_enabled),
                    updated_at = NOW()
            ");

            Schema::table('clinics', function (Blueprint $table) {
                $table->dropColumn([
                    'contract_model',
                    'commission_rate',
                    'annual_subscription_amount',
                    'annual_subscription_starts_at',
                    'annual_subscription_ends_at',
                    'rendezvous_badge_enabled',
                ]);
            });
        }
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'contract_model')) {
                $table->string('contract_model', 40)->default('cash_commission')->after('status');
                $table->decimal('commission_rate', 5, 2)->default(0)->after('contract_model');
                $table->decimal('annual_subscription_amount', 10, 2)->nullable()->after('commission_rate');
                $table->date('annual_subscription_starts_at')->nullable()->after('annual_subscription_amount');
                $table->date('annual_subscription_ends_at')->nullable()->after('annual_subscription_starts_at');
                $table->boolean('rendezvous_badge_enabled')->default(false)->after('annual_subscription_ends_at');
            }
        });

        if (Schema::hasTable('clinic_contracts')) {
            DB::statement("
                UPDATE clinics
                INNER JOIN clinic_contracts ON clinic_contracts.clinic_id = clinics.id
                SET
                    clinics.contract_model = clinic_contracts.contract_model,
                    clinics.commission_rate = clinic_contracts.commission_rate,
                    clinics.annual_subscription_amount = clinic_contracts.annual_subscription_amount,
                    clinics.annual_subscription_starts_at = clinic_contracts.annual_subscription_starts_at,
                    clinics.annual_subscription_ends_at = clinic_contracts.annual_subscription_ends_at,
                    clinics.rendezvous_badge_enabled = clinic_contracts.rendezvous_badge_enabled
            ");

            Schema::dropIfExists('clinic_contracts');
        }
    }
}
