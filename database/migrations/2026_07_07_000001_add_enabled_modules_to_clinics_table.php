<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnabledModulesToClinicsTable extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (! Schema::hasColumn('clinics', 'enabled_modules')) {
                $table->json('enabled_modules')->nullable()->after('points_category');
            }
        });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'enabled_modules')) {
                $table->dropColumn('enabled_modules');
            }
        });
    }
}
