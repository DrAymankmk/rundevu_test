<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyHospitalsTable extends Migration
{
    public function up()
    {
        Schema::create('emergency_hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('phone');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->string('address')->nullable();
            $table->double('lat', 50)->default(0)->nullable();
            $table->double('lng', 50)->default(0)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('region_id')->references('id')->on('regions')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('emergency_hospitals');
    }
}
