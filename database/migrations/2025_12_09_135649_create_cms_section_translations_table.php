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
        Schema::create('cms_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_section_id')->constrained()->onDelete('cascade');
            $table->string('locale', 10); // 'en', 'ar', 'zh-TW', 'pt-BR'
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['cms_section_id', 'locale']);
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_section_translations');
    }
};
