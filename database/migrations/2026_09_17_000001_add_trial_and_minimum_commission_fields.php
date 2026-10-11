<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrialAndMinimumCommissionFields extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'trial_ends_at')) {
                $table->date('trial_ends_at')->nullable()->after('package_end_date');
            }
        });

        Schema::table('clinic_contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('clinic_contracts', 'minimum_consultation_commission')) {
                $table->decimal('minimum_consultation_commission', 10, 2)
                    ->default(0)
                    ->after('commission_rate');
            }
        });
    }

    public function down()
    {
        Schema::table('clinic_contracts', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_contracts', 'minimum_consultation_commission')) {
                $table->dropColumn('minimum_consultation_commission');
            }
        });

        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'trial_ends_at')) {
                $table->dropColumn('trial_ends_at');
            }
        });
    }
}
