<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('conditions_ar')->nullable();
            $table->text('conditions_en')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->nullOnDelete();
            $table->decimal('price_before', 10, 2);
            $table->decimal('price_after', 10, 2);
            $table->string('status')->default('not_active'); // active | not_active
            $table->unsignedInteger('maximum_number_of_usage')->nullable();
            $table->unsignedInteger('number_of_uses')->default(0);
            $table->date('expiration_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['clinic_id', 'status']);
            $table->index('expiration_date');
            $table->index('specialty_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
