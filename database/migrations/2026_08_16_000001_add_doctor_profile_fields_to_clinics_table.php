<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctorProfileFieldsToClinicsTable extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (! Schema::hasColumn('clinics', 'info_ar')) {
                $table->text('info_ar')->nullable()->after('info');
            }

            if (! Schema::hasColumn('clinics', 'consultation_price')) {
                $table->decimal('consultation_price', 10, 2)->nullable()->after('info_ar');
            }
        });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'consultation_price')) {
                $table->dropColumn('consultation_price');
            }

            if (Schema::hasColumn('clinics', 'info_ar')) {
                $table->dropColumn('info_ar');
            }
        });
    }
}
