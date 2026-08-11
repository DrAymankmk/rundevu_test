<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialLinksToClinicsTable extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('website');
            }
            if (!Schema::hasColumn('clinics', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('clinics', 'tiktok_url')) {
                $table->string('tiktok_url')->nullable()->after('instagram_url');
            }
            if (!Schema::hasColumn('clinics', 'snapchat_url')) {
                $table->string('snapchat_url')->nullable()->after('tiktok_url');
            }
            if (!Schema::hasColumn('clinics', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('snapchat_url');
            }
        });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            foreach (['youtube_url', 'snapchat_url', 'tiktok_url', 'instagram_url', 'facebook_url'] as $column) {
                if (Schema::hasColumn('clinics', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
