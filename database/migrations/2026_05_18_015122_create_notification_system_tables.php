<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationSystemTables extends Migration
{
    public function up()
    {
        Schema::create('notification_events', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('notification_event_recipient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_recipient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('notification_event_id')->constrained()->cascadeOnDelete();
            $table->unique(['notification_recipient_id', 'notification_event_id'], 'notification_recipient_event_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_event_recipient');
        Schema::dropIfExists('notification_recipients');
        Schema::dropIfExists('notification_events');
    }
}
