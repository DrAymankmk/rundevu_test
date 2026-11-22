<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sitemap_links', function (Blueprint $table) {
            $table->string('x_default_locale', 20)
                ->nullable()
                ->after('include_alternates');
        });
    }

    public function down(): void
    {
        Schema::table('sitemap_links', function (Blueprint $table) {
            $table->dropColumn('x_default_locale');
        });
    }
};
