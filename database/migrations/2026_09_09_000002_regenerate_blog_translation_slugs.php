<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('blog_post_translations', 'slug')) {
            return;
        }

        Schema::table('blog_post_translations', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });

        $translations = DB::table('blog_post_translations')
            ->select('id', 'blog_post_id', 'locale', 'title')
            ->orderBy('id')
            ->get();
        $posts = DB::table('blog_posts')->select('id', 'slug')->get()->keyBy('id');

        foreach ($translations as $translation) {
            DB::table('blog_post_translations')
                ->where('id', $translation->id)
                ->update(['slug' => '__tmp-' . $translation->id]);
        }

        $usedSlugs = [];
        foreach ($translations as $translation) {
            $post = $posts->get($translation->blog_post_id);
            $slug = $this->makeSlug((string) $translation->title);

            if ($slug === '' && $post) {
                $slug = trim((string) $post->slug . '-' . $translation->locale, '-');
            }
            if ($slug === '') {
                $slug = 'post-' . $translation->id;
            }

            $base = $slug;
            $i = 2;
            while (isset($usedSlugs[$slug])) {
                $slug = $base . '-' . $i;
                $i++;
            }

            $usedSlugs[$slug] = true;

            DB::table('blog_post_translations')
                ->where('id', $translation->id)
                ->update(['slug' => $slug]);
        }

        Schema::table('blog_post_translations', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        //
    }

    private function makeSlug(string $value): string
    {
        $slug = Str::slug(trim($value), '-', null);

        return $slug !== '' ? $slug : Str::slug(trim($value));
    }
};
