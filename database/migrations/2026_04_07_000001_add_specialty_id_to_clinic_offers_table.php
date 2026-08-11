<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialtyIdToClinicOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinic_offers', function (Blueprint $table) {
            if (!Schema::hasColumn('clinic_offers', 'specialty_id')) {
                $table->unsignedBigInteger('specialty_id')->nullable()->after('clinic_id');
                $table->foreign('specialty_id')
                    ->references('id')
                    ->on('specialties')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinic_offers', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_offers', 'specialty_id')) {
                $table->dropForeign(['specialty_id']);
                $table->dropColumn('specialty_id');
            }
        });
    }
}
