<?php

use App\Models\CmsLanguage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_category_id')->constrained('blog_categories')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['blog_category_id', 'locale']);
            $table->index('locale');
        });

        Schema::create('blog_post_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('title');
            $table->text('summary');
            $table->longText('content');
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->unique(['blog_post_id', 'locale']);
            $table->index('locale');
        });

        $locale = $this->defaultLocale();

        if (Schema::hasColumn('blog_categories', 'description')) {
            $categories = DB::table('blog_categories')->select('id', 'name', 'description')->get();
            foreach ($categories as $category) {
                DB::table('blog_category_translations')->insert([
                    'blog_category_id' => $category->id,
                    'locale' => $locale,
                    'title' => $category->name,
                    'description' => $category->description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('blog_categories', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }

        $postColumns = ['summary', 'content', 'tags'];
        $hasPostContent = Schema::hasColumn('blog_posts', 'summary');
        if ($hasPostContent) {
            $posts = DB::table('blog_posts')->select('id', 'name', 'summary', 'content', 'tags')->get();
            foreach ($posts as $post) {
                DB::table('blog_post_translations')->insert([
                    'blog_post_id' => $post->id,
                    'locale' => $locale,
                    'title' => $post->name,
                    'summary' => $post->summary,
                    'content' => $post->content,
                    'tags' => $post->tags,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('blog_posts', function (Blueprint $table) use ($postColumns) {
                $table->dropColumn($postColumns);
            });
        }
    }

    public function down(): void
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_categories', 'description')) {
                $table->text('description')->nullable();
            }
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_posts', 'summary')) {
                $table->text('summary')->nullable();
                $table->longText('content')->nullable();
                $table->json('tags')->nullable();
            }
        });

        $locale = $this->defaultLocale();

        $categoryTranslations = DB::table('blog_category_translations')
            ->where('locale', $locale)
            ->get();
        foreach ($categoryTranslations as $translation) {
            DB::table('blog_categories')
                ->where('id', $translation->blog_category_id)
                ->update(['description' => $translation->description]);
        }

        $postTranslations = DB::table('blog_post_translations')
            ->where('locale', $locale)
            ->get();
        foreach ($postTranslations as $translation) {
            DB::table('blog_posts')
                ->where('id', $translation->blog_post_id)
                ->update([
                    'summary' => $translation->summary,
                    'content' => $translation->content,
                    'tags' => $translation->tags,
                ]);
        }

        Schema::dropIfExists('blog_post_translations');
        Schema::dropIfExists('blog_category_translations');
    }

    private function defaultLocale(): string
    {
        if (Schema::hasTable('cms_languages')) {
            $default = CmsLanguage::query()->where('is_default', true)->value('code');
            if ($default) {
                return $default;
            }

            $first = CmsLanguage::query()->where('is_active', true)->orderBy('order')->value('code');
            if ($first) {
                return $first;
            }
        }

        return config('app.fallback_locale', 'en');
    }
};
