<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToClinicOffersTable extends Migration
{
    public function up()
    {
        Schema::table('clinic_offers', function (Blueprint $table) {
            if (!Schema::hasColumn('clinic_offers', 'start_date')) {
                $table->date('start_date')->nullable()->after('discount');
            }

            if (!Schema::hasColumn('clinic_offers', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down()
    {
        Schema::table('clinic_offers', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_offers', 'end_date')) {
                $table->dropColumn('end_date');
            }

            if (Schema::hasColumn('clinic_offers', 'start_date')) {
                $table->dropColumn('start_date');
            }
        });
    }
}
