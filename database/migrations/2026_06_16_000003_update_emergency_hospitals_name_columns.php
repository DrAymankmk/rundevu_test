<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateEmergencyHospitalsNameColumns extends Migration
{
    public function up()
    {
        Schema::table('emergency_hospitals', function (Blueprint $table) {
            if (!Schema::hasColumn('emergency_hospitals', 'name_ar')) {
                $table->string('name_ar')->nullable()->after('id');
            }
            if (!Schema::hasColumn('emergency_hospitals', 'name_en')) {
                $table->string('name_en')->nullable()->after('name_ar');
            }
        });

        if (Schema::hasColumn('emergency_hospitals', 'name')) {
            DB::table('emergency_hospitals')
                ->whereNull('name_ar')
                ->update(['name_ar' => DB::raw('name'), 'name_en' => DB::raw('name')]);

            Schema::table('emergency_hospitals', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    public function down()
    {
        Schema::table('emergency_hospitals', function (Blueprint $table) {
            if (!Schema::hasColumn('emergency_hospitals', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
        });

        if (Schema::hasColumn('emergency_hospitals', 'name_ar')) {
            DB::table('emergency_hospitals')
                ->whereNull('name')
                ->update(['name' => DB::raw('name_ar')]);
        }

        Schema::table('emergency_hospitals', function (Blueprint $table) {
            if (Schema::hasColumn('emergency_hospitals', 'name_en')) {
                $table->dropColumn('name_en');
            }
            if (Schema::hasColumn('emergency_hospitals', 'name_ar')) {
                $table->dropColumn('name_ar');
            }
        });
    }
}
