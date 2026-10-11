<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('website_visit_logs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('visited_at')->index();
            $table->string('ip', 45)->nullable()->index();
            $table->string('session_id', 64)->nullable();
            $table->string('visitor_token', 64)->nullable()->index();
            $table->string('user_agent', 512)->nullable();
            $table->boolean('is_bot')->default(false);
            $table->string('bot_name', 100)->nullable();
            $table->string('page_url', 2048);
            $table->string('page_path', 512)->index();
            $table->string('page_type', 50)->default('other');
            $table->string('page_title', 255)->nullable();
            $table->string('entity_type', 50)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('entity_slug', 255)->nullable();
            $table->string('referer', 2048)->nullable();
            $table->string('referer_host', 255)->nullable()->index();
            $table->string('locale', 10)->nullable();
            $table->unsignedInteger('time_spent_seconds')->nullable();
            $table->json('events')->nullable();
            $table->unsignedInteger('events_count')->default(0);
            $table->string('country', 2)->nullable();
            $table->timestamps();

            $table->index(['is_bot', 'visited_at']);
            $table->index(['page_type', 'visited_at']);
            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_visit_logs');
    }
};
