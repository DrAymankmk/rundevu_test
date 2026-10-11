<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnglishFieldsToClinicsAndImageToOffers extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
            }

            if (!Schema::hasColumn('clinics', 'address_en')) {
                $table->text('address_en')->nullable()->after('address');
            }
        });

        Schema::table('clinic_offers', function (Blueprint $table) {
            if (!Schema::hasColumn('clinic_offers', 'image')) {
                $table->string('image')->nullable()->after('title_en');
            }
        });
    }

    public function down()
    {
        Schema::table('clinic_offers', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_offers', 'image')) {
                $table->dropColumn('image');
            }
        });

        Schema::table('clinics', function (Blueprint $table) {
            if (Schema::hasColumn('clinics', 'address_en')) {
                $table->dropColumn('address_en');
            }

            if (Schema::hasColumn('clinics', 'name_en')) {
                $table->dropColumn('name_en');
            }
        });
    }
}
