<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_contracts', function (Blueprint $table) {
            $table->string('deal_revenue_type')->nullable()->after('payment_method');
            $table->decimal('deal_revenue_amount', 10, 2)->default(0)->after('deal_revenue_type');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_contracts', function (Blueprint $table) {
            $table->dropColumn(['deal_revenue_type', 'deal_revenue_amount']);
        });
    }
};
