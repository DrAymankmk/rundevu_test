<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (! Schema::hasColumn('clinics', 'alternative_phone')) {
                $table->string('alternative_phone', 100)->nullable()->after('phone');
            }
            if (! Schema::hasColumn('clinics', 'license_number')) {
                $table->string('license_number', 100)->nullable()->after('ID_Number');
            }
            if (! Schema::hasColumn('clinics', 'medical_commercial_license')) {
                $table->string('medical_commercial_license')->nullable()->after('license_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $columns = ['alternative_phone', 'license_number', 'medical_commercial_license'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('clinics', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};