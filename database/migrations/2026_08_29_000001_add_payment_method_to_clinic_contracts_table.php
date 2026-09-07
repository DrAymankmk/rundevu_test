<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToClinicContractsTable extends Migration
{
    public function up()
    {
        Schema::table('clinic_contracts', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_contracts', 'contract_flexibility')) {
                $table->dropColumn('contract_flexibility');
            }

            if (!Schema::hasColumn('clinic_contracts', 'payment_method')) {
                $table->string('payment_method', 20)->default('cash')->after('rendezvous_badge_enabled');
            }
        });
    }

    public function down()
    {
        Schema::table('clinic_contracts', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_contracts', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
}
