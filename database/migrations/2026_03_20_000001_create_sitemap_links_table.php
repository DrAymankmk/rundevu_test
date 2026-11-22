<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sitemap_links', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('link_type', 20)->default('page'); // page | group
            $table->string('group_key', 40)->nullable()->unique();
            $table->string('path', 500)->nullable();
            $table->string('changefreq', 20)->default('weekly');
            $table->decimal('priority', 2, 1)->default(0.5);
            $table->timestamp('lastmod')->nullable();
            $table->boolean('include_alternates')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['link_type', 'is_active', 'sort_order']);
            $table->index('path');
        });

        $this->seedDefaults();
    }

    public function down(): void
    {
        Schema::dropIfExists('sitemap_links');
    }

    private function seedDefaults(): void
    {
        $now = now();
        $order = 0;

        $pages = [
            ['title' => 'Home', 'path' => '/', 'changefreq' => 'daily', 'priority' => 1.0],
            ['title' => 'About', 'path' => '/about', 'changefreq' => 'weekly', 'priority' => 0.8],
            ['title' => 'Services', 'path' => '/services', 'changefreq' => 'weekly', 'priority' => 0.8],
            ['title' => 'FAQ', 'path' => '/faq', 'changefreq' => 'weekly', 'priority' => 0.8],
            ['title' => 'Subscription', 'path' => '/subscription', 'changefreq' => 'weekly', 'priority' => 0.8],
            ['title' => 'Contact', 'path' => '/contact', 'changefreq' => 'weekly', 'priority' => 0.8],
            ['title' => 'Blog listing', 'path' => '/blog', 'changefreq' => 'weekly', 'priority' => 0.7],
            ['title' => 'Clinics listing', 'path' => '/clinics', 'changefreq' => 'weekly', 'priority' => 0.7],
            ['title' => 'Doctors listing', 'path' => '/doctors', 'changefreq' => 'weekly', 'priority' => 0.7],
            ['title' => 'Social media', 'path' => '/social-media', 'changefreq' => 'weekly', 'priority' => 0.7],
        ];

        foreach ($pages as $page) {
            DB::table('sitemap_links')->insert([
                'title' => $page['title'],
                'link_type' => 'page',
                'group_key' => null,
                'path' => $page['path'],
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
                'lastmod' => null,
                'include_alternates' => 1,
                'is_active' => 1,
                'sort_order' => $order++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $groups = [
            ['title' => 'Published blog posts', 'group_key' => 'blogs', 'changefreq' => 'weekly', 'priority' => 0.6],
            ['title' => 'Public clinics', 'group_key' => 'clinics', 'changefreq' => 'weekly', 'priority' => 0.6],
            ['title' => 'Public doctors', 'group_key' => 'doctors', 'changefreq' => 'weekly', 'priority' => 0.6],
        ];

        foreach ($groups as $group) {
            DB::table('sitemap_links')->insert([
                'title' => $group['title'],
                'link_type' => 'group',
                'group_key' => $group['group_key'],
                'path' => null,
                'changefreq' => $group['changefreq'],
                'priority' => $group['priority'],
                'lastmod' => null,
                'include_alternates' => 1,
                'is_active' => 1,
                'sort_order' => $order++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
};
