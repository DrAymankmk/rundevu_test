<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_section_id')->constrained('cms_sections')->onDelete('cascade');
            $table->string('type')->default('default'); // 'default', 'card', 'feature', 'testimonial', etc.
            $table->string('slug'); // Unique slug for frontend URLs / identification
            $table->json('settings')->nullable(); // Item-specific settings
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better query performance
            $table->index('cms_section_id');
            $table->index('order');
            $table->index('is_active');
            $table->index('type');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_items');
    }
};
