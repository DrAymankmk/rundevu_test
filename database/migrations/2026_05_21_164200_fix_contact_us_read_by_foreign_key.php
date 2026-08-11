<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixContactUsReadByForeignKey extends Migration
{
    public function up()
    {
        Schema::table('contact_us', function (Blueprint $table) {
            $table->dropForeign(['read_by']);
        });

        Schema::table('contact_us', function (Blueprint $table) {
            $table->foreign('read_by')
                ->references('id')
                ->on('clinics')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('contact_us', function (Blueprint $table) {
            $table->dropForeign(['read_by']);
        });

        Schema::table('contact_us', function (Blueprint $table) {
            $table->foreign('read_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }
}
