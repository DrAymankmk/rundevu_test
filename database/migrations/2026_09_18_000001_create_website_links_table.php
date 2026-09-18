<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_links', function (Blueprint $table) {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('type', 20)->default('social');
            $table->text('url')->nullable();
            $table->string('icon', 120)->nullable();
            $table->string('brand_color', 20)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_active', 'sort_order']);
        });

        Schema::create('website_link_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_link_id')
                ->constrained('website_links')
                ->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['website_link_id', 'locale']);
        });

        $this->seedDefaults();
    }

    public function down(): void
    {
        Schema::dropIfExists('website_link_translations');
        Schema::dropIfExists('website_links');
    }

    private function seedDefaults(): void
    {
        $now = now();
        $order = 0;

        $rows = [
            [
                'key' => 'apple',
                'type' => 'store',
                'url' => 'https://apps.apple.com/us/app/randevu-%D8%B1%D8%A7%D9%86%D8%AF%D9%8A%D9%81%D9%88/id6761128352',
                'icon' => 'fab fa-apple',
                'brand_color' => '#000000',
                'is_active' => true,
                'translations' => [
                    'en' => ['title' => 'App Store', 'description' => 'Download the Rundevo iOS app.'],
                    'ar' => ['title' => 'آب ستور', 'description' => 'حمّل تطبيق رنديفو على iOS.'],
                ],
            ],
            [
                'key' => 'google_play',
                'type' => 'store',
                'url' => 'https://play.google.com/store/apps/details?id=com.takaful.rendezvous',
                'icon' => 'fab fa-google-play',
                'brand_color' => '#34A853',
                'is_active' => true,
                'translations' => [
                    'en' => ['title' => 'Google Play', 'description' => 'Download the Rundevo Android app.'],
                    'ar' => ['title' => 'جوجل بلاي', 'description' => 'حمّل تطبيق رنديفو على أندرويد.'],
                ],
            ],
            [
                'key' => 'facebook',
                'type' => 'social',
                'url' => env('SOCIAL_FACEBOOK_URL', ''),
                'icon' => 'fab fa-facebook-f',
                'brand_color' => '#1877F2',
                'is_active' => filled(env('SOCIAL_FACEBOOK_URL', '')),
                'translations' => [
                    'en' => ['title' => 'Facebook', 'description' => 'News, updates, and community posts.'],
                    'ar' => ['title' => 'فيسبوك', 'description' => 'أخبار وتحديثات ومنشورات المجتمع.'],
                ],
            ],
            [
                'key' => 'instagram',
                'type' => 'social',
                'url' => env('SOCIAL_INSTAGRAM_URL', 'https://www.instagram.com/runde_vo'),
                'icon' => 'fab fa-instagram',
                'brand_color' => '#E4405F',
                'is_active' => true,
                'translations' => [
                    'en' => ['title' => 'Instagram', 'description' => 'Visual stories, reels, and behind-the-scenes content.'],
                    'ar' => ['title' => 'إنستغرام', 'description' => 'قصص مرئية وريلز ومحتوى من خلف الكواليس.'],
                ],
            ],
            [
                'key' => 'snapchat',
                'type' => 'social',
                'url' => env('SOCIAL_SNAPCHAT_URL', 'https://www.snapchat.com/@rundev'),
                'icon' => 'fab fa-snapchat',
                'brand_color' => '#FFFC00',
                'is_active' => true,
                'translations' => [
                    'en' => ['title' => 'Snapchat', 'description' => 'Quick updates and daily highlights.'],
                    'ar' => ['title' => 'سناب شات', 'description' => 'تحديثات سريعة ولمحات يومية.'],
                ],
            ],
            [
                'key' => 'twitter',
                'type' => 'social',
                'url' => env('SOCIAL_TWITTER_URL', ''),
                'icon' => 'fab fa-x-twitter',
                'brand_color' => '#000000',
                'is_active' => filled(env('SOCIAL_TWITTER_URL', '')),
                'translations' => [
                    'en' => ['title' => 'X (Twitter)', 'description' => 'Announcements and real-time updates.'],
                    'ar' => ['title' => 'إكس (تويتر)', 'description' => 'إعلانات وتحديثات فورية.'],
                ],
            ],
            [
                'key' => 'tiktok',
                'type' => 'social',
                'url' => env('SOCIAL_TIKTOK_URL', 'https://www.tiktok.com/@rundevo.app'),
                'icon' => 'fab fa-tiktok',
                'brand_color' => '#010101',
                'is_active' => true,
                'translations' => [
                    'en' => ['title' => 'TikTok', 'description' => 'Short videos, tips, and product highlights.'],
                    'ar' => ['title' => 'تيك توك', 'description' => 'فيديوهات قصيرة ونصائح وأبرز المزايا.'],
                ],
            ],
            [
                'key' => 'youtube',
                'type' => 'social',
                'url' => env('SOCIAL_YOUTUBE_URL', ''),
                'icon' => 'fab fa-youtube',
                'brand_color' => '#FF0000',
                'is_active' => filled(env('SOCIAL_YOUTUBE_URL', '')),
                'translations' => [
                    'en' => ['title' => 'YouTube', 'description' => 'Tutorials, demos, and feature walkthroughs.'],
                    'ar' => ['title' => 'يوتيوب', 'description' => 'شروحات وعروض توضيحية للميزات.'],
                ],
            ],
            [
                'key' => 'linkedin',
                'type' => 'social',
                'url' => env('SOCIAL_LINKEDIN_URL', ''),
                'icon' => 'fab fa-linkedin-in',
                'brand_color' => '#0A66C2',
                'is_active' => filled(env('SOCIAL_LINKEDIN_URL', '')),
                'translations' => [
                    'en' => ['title' => 'LinkedIn', 'description' => 'Company news and professional updates.'],
                    'ar' => ['title' => 'لينكدإن', 'description' => 'أخبار الشركة وتحديثات مهنية.'],
                ],
            ],
            [
                'key' => 'whatsapp',
                'type' => 'social',
                'url' => env('SOCIAL_WHATSAPP_URL', ''),
                'icon' => 'fab fa-whatsapp',
                'brand_color' => '#25D366',
                'is_active' => filled(env('SOCIAL_WHATSAPP_URL', '')),
                'translations' => [
                    'en' => ['title' => 'WhatsApp', 'description' => 'Chat with us for quick support.'],
                    'ar' => ['title' => 'واتساب', 'description' => 'تواصل معنا للحصول على دعم سريع.'],
                ],
            ],
        ];

        foreach ($rows as $row) {
            $id = DB::table('website_links')->insertGetId([
                'key' => $row['key'],
                'type' => $row['type'],
                'url' => $row['url'] ?: null,
                'icon' => $row['icon'],
                'brand_color' => $row['brand_color'],
                'sort_order' => $order++,
                'is_active' => $row['is_active'] ? 1 : 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($row['translations'] as $locale => $translation) {
                DB::table('website_link_translations')->insert([
                    'website_link_id' => $id,
                    'locale' => $locale,
                    'title' => $translation['title'],
                    'description' => $translation['description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
};
