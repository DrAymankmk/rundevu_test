<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconAndImageToSpecialtiesTable extends Migration
{
    public function up()
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->string('icon', 120)->nullable()->after('name_en');
            $table->string('image')->nullable()->after('icon');
        });
    }

    public function down()
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->dropColumn(['icon', 'image']);
        });
    }
}
