<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->string('generated_code')->unique();
            $table->boolean('used')->default(false);
            $table->dateTime('used_date_time')->nullable();
            $table->unsignedBigInteger('used_by')->nullable();
            $table->decimal('price_after', 10, 2)->nullable();
            $table->decimal('platform_entitlement', 10, 2)->nullable();
            $table->decimal('clinic_net', 10, 2)->nullable();
            $table->string('deal_revenue_type')->nullable();
            $table->decimal('deal_revenue_amount', 10, 2)->nullable();
            $table->string('settlement_status')->default('pending'); // pending | transfer_completed
            $table->dateTime('settled_at')->nullable();
            $table->timestamps();

            $table->foreign('used_by')->references('id')->on('clinics')->nullOnDelete();
            $table->index('patient_id');
            $table->index('used');
            $table->index('settlement_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_deals');
    }
};