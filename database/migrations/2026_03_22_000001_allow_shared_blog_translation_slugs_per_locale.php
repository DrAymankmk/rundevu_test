<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('blog_post_translations') || ! Schema::hasColumn('blog_post_translations', 'slug')) {
            return;
        }

        $this->dropIndexIfExists('blog_post_translations', 'blog_post_translations_slug_unique');
        $this->dropIndexIfExists('blog_post_translations', 'slug');

        if (! $this->indexExists('blog_post_translations', 'blog_post_translations_locale_slug_unique')) {
            Schema::table('blog_post_translations', function (Blueprint $table) {
                $table->unique(['locale', 'slug']);
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('blog_post_translations') || ! Schema::hasColumn('blog_post_translations', 'slug')) {
            return;
        }

        $this->dropIndexIfExists('blog_post_translations', 'blog_post_translations_locale_slug_unique');

        if (! $this->indexExists('blog_post_translations', 'blog_post_translations_slug_unique')) {
            Schema::table('blog_post_translations', function (Blueprint $table) {
                $table->unique('slug');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();
        $result = DB::selectOne(
            'SELECT COUNT(*) AS aggregate
             FROM information_schema.statistics
             WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $indexName]
        );

        return (int) ($result->aggregate ?? 0) > 0;
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (! $this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($indexName) {
            $blueprint->dropUnique($indexName);
        });
    }
};
