<?php

namespace Database\Seeders;

use App\Models\CmsItem;
use App\Models\CmsItemTranslation;
use App\Models\CmsLink;
use App\Models\CmsLinkTranslation;
use App\Models\CmsPage;
use App\Models\CmsPageTranslation;
use App\Models\CmsSection;
use App\Models\CmsSectionTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use App\Models\CmsLanguage;
use RuntimeException;

class CmsSeeder extends Seeder
{
    /** @var list<string> */
    private const CMS_MIGRATION_PATHS = [
        'database/migrations/2025_12_09_134000_create_languages_table.php',
        'database/migrations/2025_12_09_135213_create_cms_pages_table.php',
        'database/migrations/2025_12_09_135619_create_cms_page_translations_table.php',
        'database/migrations/2025_12_09_135632_create_cms_sections_table.php',
        'database/migrations/2025_12_09_135649_create_cms_section_translations_table.php',
        'database/migrations/2025_12_09_135653_create_cms_items_table.php',
        'database/migrations/2025_12_09_135655_create_cms_item_translations_table.php',
        'database/migrations/2025_12_09_140559_create_cms_links_table.php',
        'database/migrations/2025_12_09_140946_create_cms_link_translations_table.php',
         'database/migrations/2026_01_18_140342_create_media_table.php',
    ];

    /** Tables cleared before re-seeding (order only used for existence check). */
    private const CMS_CONTENT_TABLES = [
        'cms_link_translations',
        'cms_links',
        'cms_item_translations',
        'cms_items',
        'cms_section_translations',
        'cms_sections',
        'cms_page_translations',
        'cms_pages',
        'media',
    ];

    private function allCmsContentTablesExist(): bool
    {
        foreach (self::CMS_CONTENT_TABLES as $table) {
            if (! Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Run CMS migrations when any content table is missing; otherwise leave schema as-is.
     */
    private function ensureCmsMigrations(): void
    {
        if ($this->allCmsContentTablesExist()) {
            return;
        }

        $this->command?->warn('CMS tables missing — running CMS migrations…');

        foreach (self::CMS_MIGRATION_PATHS as $path) {
            Artisan::call('migrate', [
                '--path' => $path,
                '--force' => true,
            ]);
        }

        if (! $this->allCmsContentTablesExist()) {
            throw new RuntimeException(
                'CMS tables are still missing after migrate. Fix your database or run: php artisan migrate --force'
            );
        }
    }

    /**
     * Empty CMS page graph (keeps e.g. cms_languages intact).
     */
    private function clearCmsContent(): void
    {
        Schema::disableForeignKeyConstraints();
        try {
            // Translation / child tables first; MySQL rejects TRUNCATE parents while FKs exist.
            CmsLinkTranslation::truncate();
            CmsLink::truncate();
            CmsItemTranslation::truncate();
            CmsItem::truncate();
            CmsSectionTranslation::truncate();
            CmsSection::truncate();
            CmsPageTranslation::truncate();
            CmsPage::truncate();
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->ensureCmsMigrations();
        $this->clearCmsContent();

// languages data
$languages = [
    [
        'code' => 'en',
        'name' => 'English',
         'native_name' => 'English',
        'direction' => 'ltr',
        'flag' => '🇺🇸',
        'is_default' => false,
        'is_active' => true,
        'order' => 1,
    ],

    [
        'code' => 'ar',
        'name' => 'Arabic',
        'native_name' => 'العربية',
        'direction' => 'rtl',
        'flag' => '🇸🇦',
        'is_default' => true,
        'is_active' => true,
        'order' => 2,
    ],

];
foreach ($languages as $language) {
    CmsLanguage::query()->updateOrCreate(
        ['code' => $language['code']],
        $language
    );
}


// ------------------------ home page data  ----------------------

    // home page
    $homePage = CmsPage::create([
        'slug' => 'home',
        'name' => 'Home',
        'is_active' => true,
        'order' => 1,
    ]);

    $homePage->translations()->create([
        'locale' => 'en',
        'title' => 'Home',
        'meta_description' => 'Home page',
        'meta_keywords' => 'home, page',
    ]);

	$homePage->translations()->create([
		'locale' => 'ar',
		'title' => 'الصفحة الرئيسية',
		'meta_description' => 'الصفحة الرئيسية',
		'meta_keywords' => 'الصفحة الرئيسية',
	]);





    //-------------------------------- home page hero section --------------------------------
    $homePageSection1 = $homePage->sections()->create([
        'name' => 'Hero',
        'type' => 'hero',
        'template' => 'hero',
        'settings' => [],
        'is_active' => true,
        'order' => 1,
    ]);

    // translations
    $homePageSection1->translations()->create([
        'locale' => 'en',
        'title' => 'Hero',
        'subtitle' => 'Hero subtitle',
        'description' => 'Hero description',
    ]);
    $homePageSection1->translations()->create([
        'locale' => 'ar',
        'title' => 'الصفحة الرئيسية',
        'subtitle' => 'الصفحة الرئيسية',
        'description' => 'الصفحة الرئيسية',
    ]);

	// item1
	$homePageSection1Item1 = $homePageSection1->items()->create([
	'type' => 'text',
	'slug' => 'hero-text',
	'settings' => [],
	'is_active' => true,
	'order' => 1,
	]);

	$homePageSection1Item1->translations()->create([
	'locale' => 'en',
	'title' => 'Hero Text',
	'content' => 'Welcome to our website',
	'icon' => 'fa-solid fa-home',
	]);

	$homePageSection1Item1->translations()->create([
	'locale' => 'ar',
	'title' => 'النص الرئيسي',
	'content' => 'مرحبا بك في موقعنا',
	'icon' => 'fa-solid fa-home',
	]);

	// item2
	$homePageSection1Item2 = $homePageSection1->items()->create([
		'type' => 'text',
		'slug' => 'hero-text-2',
		'settings' => [],
		'is_active' => true,
		'order' => 2,
	]);

	$homePageSection1Item2->translations()->create([
		'locale' => 'en',
		'title' => 'Hero Text 2',
		'content' => 'Welcome to our website 2',
		'icon' => 'fa-solid fa-home',
	]);

	$homePageSection1Item2->translations()->create([
		'locale' => 'ar',
		'title' => 'النص الرئيسي 2',
		'content' => 'مرحبا بك في موقعنا 2',
		'icon' => 'fa-solid fa-home',
	]);

	// item3
	$homePageSection1Item3 = $homePageSection1->items()->create([
		'type' => 'text',
		'slug' => 'hero-text-3',
		'settings' => [],
		'is_active' => true,
		'order' => 3,
	]);

	$homePageSection1Item3->translations()->create([
		'locale' => 'en',
		'title' => 'Hero Text 3',
		'content' => 'Welcome to our website 3',
		'icon' => 'fa-solid fa-home',
	]);

	$homePageSection1Item3->translations()->create([
		'locale' => 'ar',
		'title' => 'النص الرئيسي 3',
		'content' => 'مرحبا بك في موقعنا 3',
		'icon' => 'fa-solid fa-home',
	]);

// ------------------------ home page features section  ----------------------

	// home page features section
	$homePageSection2 = $homePage->sections()->create([
		'name' => 'Features',
		'type' => 'features',
		'template' => 'features',
		'settings' => [],
		'is_active' => true,
		'order' => 2,
	]);

	// translations
	$homePageSection2->translations()->create([
		'locale' => 'en',
		'title' => 'Features',
		'description' => 'Features description',
	]);

	$homePageSection2->translations()->create([
		'locale' => 'ar',
		'title' => 'الميزات',
		'description' => 'الميزات الوصف',
	]);

	// item1
	$homePageSection2Item1 = $homePageSection2->items()->create([
		'type' => 'feature',
		'slug' => 'feature-1',
		'settings' => [],
		'is_active' => true,
		'order' => 1,
	]);

	$homePageSection2Item1->translations()->create([
		'locale' => 'en',
		'title' => 'Feature 1',
		'content' => 'Feature 1 description',
	]);
	$homePageSection2Item1->translations()->create([
		'locale' => 'ar',
		'title' => '1. تطبيق للمريض (ios & Android ) ',
		'content' => 'للبحث والحجز والمحادثة والتقييم ',
	]);

	// item2
	$homePageSection2Item2 = $homePageSection2->items()->create([
		'type' => 'feature',
		'slug' => 'feature-2',
		'settings' => [],
		'is_active' => true,
		'order' => 2,
	]);
	$homePageSection2Item2->translations()->create([
		'locale' => 'en',
		'title' => 'Feature 2',
		'content' => 'Feature 2 description',
	]);
	$homePageSection2Item2->translations()->create([
		'locale' => 'ar',
		'title' => '2.لوحة تحكم مدير العيادة ',
		'content' => 'لادارة الأطباء والورديات والشكاوي',
	]);


	// item3
	$homePageSection2Item3 = $homePageSection2->items()->create([
		'type' => 'feature',
		'slug' => 'feature-3',
		'settings' => [],
		'is_active' => true,
		'order' => 3,
	]);
	$homePageSection2Item3->translations()->create([
		'locale' => 'en',
		'title' => 'Feature 3',
		'content' => 'Feature 3 description',
	]);
	$homePageSection2Item3->translations()->create([
		'locale' => 'ar',
		'title' => '3.مدير التطبيق',
		'content' => 'مدير التطبيق يقوم بأداء رقابي عام ومعالجة شكاوي النظام',
	]);


// ------------------------ home page about us section  ----------------------


	// home page about us section
	$homePageSection3 = $homePage->sections()->create([
		'name' => 'About Us',
		'type' => 'about-us',
		'template' => 'about-us',
		'settings' => [],
		'is_active' => true,
		'order' => 4,
	]);

	// translations
	$homePageSection3->translations()->create([
		'locale' => 'en',
		'title' => 'About Us',
		'subtitle' => 'About Us',
		'description' => 'About Us description',
	]);
	$homePageSection3->translations()->create([
		'locale' => 'ar',
		'title' => 'عن النظام',
		'subtitle' => 'عنا',
		'description' => '<p> نظام رانديفو هو منصة رقمية متكاملة لإدارة العيادات والمراكز الطبية، تهدف إلى رقمنة رحلة المريض بالكامل من لحظة البحث عن الطبيب وحتى تقييم الخدمة. </p>
		<ul>
		<li>تواصل لحظية بين المريض وموظفي الاستقبال،</li>
		<li>منح مديري العيادات أدوات تحكم ذكية لإدارة الكوادر الطبية والمواعيد</li>
		<li>يضمن تجربة طبية سلسة ومنظمة تعتمد على الشفافية والكفاءة</li>
		<li>مراقبة الأداء العام ومعالجة الشكاوي</li>
		<li>تقييم منفصل للاطباء</li>
		</ul>',
	]);


// ------------------------ home page services section  ----------------------

	// home page services section
	$homePageSection4 = $homePage->sections()->create([
		'name' => 'Services',
		'type' => 'services',
		'template' => 'services',
		'settings' => [],
		'is_active' => true,
		'order' => 5,
	]);

	// translations
	$homePageSection4->translations()->create([
		'locale' => 'en',
		'title' => 'Services',
		'subtitle' => 'Our Services',
		'description' => 'Services description',
	]);
	$homePageSection4->translations()->create([
		'locale' => 'ar',
		'title' => 'الخدمات',
		'subtitle' => 'خدماتنا',
		'description' => 'الخدمات الوصف',
	]);

	// item1
	$homePageSection4Item1 = $homePageSection4->items()->create([
		'type' => 'service',
		'slug' => 'service-1',
		'settings' => [],
		'is_active' => true,
		'order' => 1,
	]);
	$homePageSection4Item1->translations()->create([
		'locale' => 'en',
		'title' => 'Service 1',
		'content' => 'Service 1 description',
	]);
	$homePageSection4Item1->translations()->create([
		'locale' => 'ar',
		'title' => '1.إدارة الحجز الذكي ',
		'content' => 'نظام مرن لاختيار الطبيب والوقت المناسب بناء علي ورديات محددة مسبقا ',
	]);

	// item2
	$homePageSection4Item2 = $homePageSection4->items()->create([
		'type' => 'service',
		'slug' => 'service-2',
		'settings' => [],
		'is_active' => true,
		'order' => 2,
	]);
	$homePageSection4Item2->translations()->create([
		'locale' => 'en',
		'title' => 'Service 2',
		'content' => 'Service 2 description',
	]);
	$homePageSection4Item2->translations()->create([
		'locale' => 'ar',
		'title' => '2.نظام المرافقين القصر ',
		'content' => 'إمكانية إضافة حتي ٣ مرافقين من الأبناء (تحت سن ١٨ عام ) لكل حساب مريض لتسهيل حجز العائلة',
	]);


	// item3
	$homePageSection4Item3 = $homePageSection4->items()->create([
		'type' => 'service',
		'slug' => 'service-3',
		'settings' => [],
		'is_active' => true,
		'order' => 3,
	]);
	$homePageSection4Item3->translations()->create([
		'locale' => 'en',
		'title' => 'Service 3',
		'content' => 'Service 3 description',
	]);
	$homePageSection4Item3->translations()->create([
		'locale' => 'ar',
		'title' => '3.التواصل الفوري ',
		'content' => 'شات مباشر مع موظف الاستقبال للاستفسارات السريعة وتنسيق الحجوزات',
	]);


	// item4
	$homePageSection4Item4 = $homePageSection4->items()->create([
		'type' => 'service',
		'slug' => 'service-4',
		'settings' => [],
		'is_active' => true,
		'order' => 4,
	]);
	$homePageSection4Item4->translations()->create([
		'locale' => 'en',
		'title' => 'Service 4',
		'content' => 'Service 4 description',
	]);
	$homePageSection4Item4->translations()->create([
		'locale' => 'ar',
		'title' => '4.إدارة الشكاوي المتعددة ',
		'content' => 'قنوات مخصصه لايصال صوت المريض لمدير العيادة (الخدمات الطبية) او لمدير التطبيق (للمشاكل التقنية )',
	]);

	// item5
	$homePageSection4Item5 = $homePageSection4->items()->create([
		'type' => 'service',
		'slug' => 'service-5',
		'settings' => [],
		'is_active' => true,
		'order' => 5,
	]);
	$homePageSection4Item5->translations()->create([
		'locale' => 'en',
		'title' => 'Service 5',
		'content' => 'Service 5 description',
	]);
	$homePageSection4Item5->translations()->create([
		'locale' => 'ar',
		'title' => '5.نظام التقييم الشامل ',
		'content' => 'تقييم منفصل للأطباء (الجانب الطبي) والعيادات (الجانب التنظيمي ) لضمان جودة الخدمة ',
	]);

	// item6
	$homePageSection4Item6 = $homePageSection4->items()->create([
		'type' => 'service',
		'slug' => 'service-6',
		'settings' => [],
		'is_active' => true,
		'order' => 6,
	]);
	$homePageSection4Item6->translations()->create([
		'locale' => 'en',
		'title' => 'Service 6',
		'content' => 'Service 6 description',
	]);
	$homePageSection4Item6->translations()->create([
		'locale' => 'ar',
		'title' => '6.لوحات تحكم تخصصية ',
		'content' => 'واجهات مخصصة لكل دور وظيفي لضمان سرعة الإنجاز وسهولة الاستخدام',
	]);

// ------------------------ home page why choose us section  ----------------------

	// home page why choose us section
	$homePageSection5 = $homePage->sections()->create([
		'name' => 'Why Choose Us',
		'type' => 'why-choose-us',
		'template' => 'why-choose-us',
		'settings' => [],
		'is_active' => true,
		'order' => 7,
	]);
	$homePageSection5->translations()->create([
		'locale' => 'en',
		'title' => 'Why Choose Us',
		'subtitle' => 'Why Choose Us subtitle',
		'description' => 'Why Choose Us description',
	]);
	$homePageSection5->translations()->create([
		'locale' => 'ar',
		'title' => 'لماذا تختارنا',
		'subtitle' => 'لماذا تختارنا',
		'description' =>  'نحن لا نقدم مجرد تطبيق للحجز بل نقدم شريكا تقنيا لنجاج عيادتك ',
	]);

	// item1
	$homePageSection5Item1 = $homePageSection5->items()->create([
		'type' => 'why-choose-us',
		'slug' => 'why-choose-us-1',
		'settings' => [],
		'is_active' => true,
		'order' => 1,
	]);
	$homePageSection5Item1->translations()->create([
		'locale' => 'en',
		'title' => 'Why Choose Us 1',
		'content' => 'Why Choose Us 1 description',
	]);
	$homePageSection5Item1->translations()->create([
		'locale' => 'ar',
		'title' => '1.تنظيم بلا فوضي ',
		'content' => 'ودع السجلات الورقية؛ فكل شيء من جداول الأطباء إلى بيانات المرضى متاح بضغطة زر',
	]);

	// item2
	$homePageSection5Item2 = $homePageSection5->items()->create([
		'type' => 'why-choose-us',
		'slug' => 'why-choose-us-2',
		'settings' => [],
		'is_active' => true,
		'order' => 2,
	]);
	$homePageSection5Item2->translations()->create([
		'locale' => 'en',
		'title' => 'Why Choose Us 2',
		'content' => 'Why Choose Us 2 description',
	]);
	$homePageSection5Item2->translations()->create([
		'locale' => 'ar',
		'title' => '2.تعزيز الثقة',
		'content' => 'من خلال نظام التقييمات والشكاوى، نبني جسراً من المصداقية بينك وبين مرضاك',
	]);

	// item3
	$homePageSection5Item3 = $homePageSection5->items()->create([
		'type' => 'why-choose-us',
		'slug' => 'why-choose-us-3',
		'settings' => [],
		'is_active' => true,
		'order' => 3,
	]);
	$homePageSection5Item3->translations()->create([
		'locale' => 'en',
		'title' => 'Why Choose Us 3',
		'content' => 'Why Choose Us 3 description',
	]);
	$homePageSection5Item3->translations()->create([
		'locale' => 'ar',
		'title' => '3.تواصل فعال',
		'content' => 'نلغي حواجز الاتصال من خلال الشات المباشر، مما يقلل من نسب إلغاء المواعيد.',
	]);

	// item4
	$homePageSection5Item4 = $homePageSection5->items()->create([
		'type' => 'why-choose-us',
		'slug' => 'why-choose-us-4',
		'settings' => [],
		'is_active' => true,
		'order' => 4,
	]);
	$homePageSection5Item4->translations()->create([
		'locale' => 'en',
		'title' => 'Why Choose Us 4',
		'content' => 'Why Choose Us 4 description',
	]);
	$homePageSection5Item4->translations()->create([
		'locale' => 'ar',
		'title' => '4.إدارة عائلية متكاملة',
		'content' => 'ميزة إضافة المرافقين تجعل تطبيقنا الخيار الأول للأهالي لإدارة مواعيد أطفالهم بسهولة',
	]);

	// item5
	$homePageSection5Item5 = $homePageSection5->items()->create([
		'type' => 'why-choose-us',
		'slug' => 'why-choose-us-5',
		'settings' => [],
		'is_active' => true,
		'order' => 5,
	]);
	$homePageSection5Item5->translations()->create([
		'locale' => 'en',
		'title' => 'Why Choose Us 5',
		'content' => 'Why Choose Us 5 description',
	]);
	$homePageSection5Item5->translations()->create([
		'locale' => 'ar',
		'title' => '5.تحليل الأداء',
		'content' => 'لوحات التحكم توفر لمدير العيادة رؤية واضحة حول كفاءة الأطباء ومستوى رضا العملاء.',
	]);

// ------------------------ home page download-app section  ----------------------

	// home page download-app section
	$homePageSection6 = $homePage->sections()->create([
		'name' => 'Download App',
		'type' => 'download-app',
		'template' => 'download-app',
		'settings' => [],
		'is_active' => true,
		'order' => 8,
	]);
	$homePageSection6->translations()->create([
		'locale' => 'en',
		'title' => 'Download App',
		'subtitle' => 'Download App subtitle',
		'description' => 'Download App description',
	]);
	$homePageSection6->translations()->create([
		'locale' => 'ar',
		'title' => 'تحميل التطبيق',
		'subtitle' => 'تحميل التطبيق',
		'description' => 'سواء كنت تبحث عن أفضل الأطباء أو ترغب في إدارة مواعيد عائلتك بذكاء، تطبيقنا يوفر لك كل ما تحتاجه في مكان واحد',
	]);


 $homePageSection7 = $homePage->sections()->create([
        'name' => 'Plans',
        'type' => 'plans',
        'template' => 'plans',
        'settings' => [],
        'is_active' => true,
        'order' => 9,
    ]);

    // translations
    $homePageSection7->translations()->create([
        'locale' => 'en',
        'title' => 'Plans',
        'subtitle' => 'Plans subtitle',
        'description' => 'Plans description',
    ]);
    $homePageSection7->translations()->create([
        'locale' => 'ar',
        'title' => 'الخطط',
        'subtitle' => 'الخطط',
        'description' => 'الخطط',
    ]);




// --------------- about us page data ----------------------

	// about us page
	$aboutUsPage = CmsPage::create([
	'slug' => 'about',
	'name' => 'About Us',
	'is_active' => true,
	'order' => 2,
	]);

	$aboutUsPage->translations()->create([
		'locale' => 'en',
		'title' => 'About Us',
		'meta_description' => 'About Us description',
		'meta_keywords' => 'About Us description',
	]);
	$aboutUsPage->translations()->create([
		'locale' => 'ar',
		'title' => 'عن النظام',
		'meta_description' => 'عن النظام',
		'meta_keywords' => 'عن النظام, النظام',
	]);

	// about us page sections
	$aboutUsPageSection1 = $aboutUsPage->sections()->create([
		'name' => 'About Us',
		'type' => 'about-us',
		'template' => 'about-us',
		'settings' => [],
		'is_active' => true,
		'order' => 4,
	]);

	// translations
	$aboutUsPageSection1->translations()->create([
		'locale' => 'en',
		'title' => 'About Us',
        'subtitle' => 'About Us subtitle',
		'description' => 'About Us description',
	]);
	$aboutUsPageSection1->translations()->create([
		'locale' => 'ar',
		'title' => 'عن النظام',
		'subtitle' => 'عنا',
		'description' => '<p> نظام رانديفو هو منصة رقمية متكاملة لإدارة العيادات والمراكز الطبية، تهدف إلى رقمنة رحلة المريض بالكامل من لحظة البحث عن الطبيب وحتى تقييم الخدمة. </p>
		<ul>
		<li>تواصل لحظية بين المريض وموظفي الاستقبال،</li>
		<li>منح مديري العيادات أدوات تحكم ذكية لإدارة الكوادر الطبية والمواعيد</li>
		<li>يضمن تجربة طبية سلسة ومنظمة تعتمد على الشفافية والكفاءة</li>
		<li>مراقبة الأداء العام ومعالجة الشكاوي</li>
		<li>تقييم منفصل للاطباء</li>
		</ul>',
	]);


// about us page features section
	$aboutUsPageSection2 = $aboutUsPage->sections()->create([
		'name' => 'Features',
		'type' => 'features',
		'template' => 'features',
		'settings' => [],
		'is_active' => true,
		'order' => 5,
	]);

	// translations
	$aboutUsPageSection2->translations()->create([
		'locale' => 'en',
		'title' => 'Features',
		'description' => 'Features description',
	]);
	$aboutUsPageSection2->translations()->create([
		'locale' => 'ar',
		'title' => 'الميزات',
		'description' => 'الميزات الوصف',
	]);

	// item1
	$aboutUsPageSection2Item1 = $aboutUsPageSection2->items()->create([
		'type' => 'feature',
		'slug' => 'feature-1',
		'settings' => [],
		'is_active' => true,
		'order' => 1,
	]);
	$aboutUsPageSection2Item1->translations()->create([
		'locale' => 'en',
		'title' => 'Feature 1',
		'content' => 'Feature 1 description',
	]);
	$aboutUsPageSection2Item1->translations()->create([
		'locale' => 'ar',
		'title' => '1. تطبيق للمريض (ios & Android ) ',
		'content' => 'للبحث والحجز والمحادثة والتقييم ',
	]);

	// item2
	$aboutUsPageSection2Item2 = $aboutUsPageSection2->items()->create([
		'type' => 'feature',
		'slug' => 'feature-2',
		'settings' => [],
		'is_active' => true,
		'order' => 2,
	]);
	$aboutUsPageSection2Item2->translations()->create([
	'locale' => 'en',
	'title' => 'Feature 2',
	'content' => 'Feature 2 description',
	]);
	$aboutUsPageSection2Item2->translations()->create([
	'locale' => 'ar',
	'title' => '2. لوحة تحكم مدير العيادة ',
	'content' => 'لادارة الأطباء والورديات والشكاوي',
	]);

	// item3
	$aboutUsPageSection2Item3 = $aboutUsPageSection2->items()->create([
		'type' => 'feature',
		'slug' => 'feature-3',
		'settings' => [],
		'is_active' => true,
		'order' => 3,
	]);

	$aboutUsPageSection2Item3->translations()->create([
		'locale' => 'en',
		'title' => 'Feature 3',
		'content' => 'Feature 3 description',
	]);
	$aboutUsPageSection2Item3->translations()->create([
		'locale' => 'ar',
		'title' => '3. مدير التطبيق',
		'content' => 'مدير التطبيق يقوم بأداء رقابي عام ومعالجة شكاوي النظام',
	]);

//---------------------------------

	// about us page values section
	$aboutUsPageSection3 = $aboutUsPage->sections()->create([
		'name' => 'Values',
		'type' => 'values',
		'template' => 'values',
		'settings' => [],
		'is_active' => true,
		'order' => 6,
	]);

	// translations
	$aboutUsPageSection3->translations()->create([
		'locale' => 'en',
		'title' => 'Values',
		'subtitle' => 'Values subtitle',
		'description' => 'Values description',
	]);
	$aboutUsPageSection3->translations()->create([
		'locale' => 'ar',
		'title' => 'القيم',
		'subtitle' => 'القيم',
		'description'=>'هدفنا هو "تحويل عملية الحجز الطبي من عبء إداري إلى تجربة رقمية تتسم بالسهولة، الشفافية، والاحترافية لجميع الأطراف."'
	]);

	// item1
	$aboutUsPageSection3Item1 = $aboutUsPageSection3->items()->create([
		'type' => 'value',
		'slug' => 'value-1',
		'settings' => [],
		'is_active' => true,
		'order' => 1,
	]);

	$aboutUsPageSection3Item1->translations()->create([
		'locale' => 'en',
		'title' => 'Value 1',
		'content' => 'Value 1 description',
	]);
	$aboutUsPageSection3Item1->translations()->create([
		'locale' => 'ar',
		'title' => 'رقمنة العمليات الإدارية',
		'content' => 'يهدف النظام إلى تحويل إدارة المواعيد من الطرق التقليدية (الورقية أو الهاتفية) إلى نظام رقمي بالكامل. هذا يقلل من الأخطاء البشرية في تسجيل المواعيد، ويضمن تنظيم ورديات الأطباء (Shifts) بشكل دقيق يمنع التداخل أو الازدحام.',
	]);

	// item 2
	$aboutUsPageSection3Item2 = $aboutUsPageSection3->items()->create([
		'type' => 'value',
		'slug' => 'value-2',
		'settings' => [],
		'is_active' => true,
		'order' => 2,
	]);

	$aboutUsPageSection3Item2->translations()->create([
		'locale' => 'en',
		'title' => 'Value 2',
		'content' => 'Value 2 description',
	]);
	$aboutUsPageSection3Item2->translations()->create([
		'locale' => 'ar',
		'title' => 'تحسين تجربة المريض (Patient Centricity):',
		'content' => 'تسهيل رحلة المريض من خلال توفير منصة واحدة تمكنه من البحث عن العيادات، اختيار الطبيب بناءً على التقييمات، وإتمام الحجز في ثوانٍ. ميزة إضافة المرافقين تهدف تحديداً إلى جعل التطبيق "صديقاً للعائلة"، حيث يمكن لولي الأمر إدارة مواعيد أطفاله من حساب واحد.',
	]);

	// item 3
	$aboutUsPageSection3Item3 = $aboutUsPageSection3->items()->create([
		'type' => 'value',
		'slug' => 'value-3',
		'settings' => [],
		'is_active' => true,
		'order' => 3,
	]);

	$aboutUsPageSection3Item3->translations()->create([
		'locale' => 'en',
		'title' => 'Value 3',
		'content' => 'Value 3 description',
	]);
	$aboutUsPageSection3Item3->translations()->create([
		'locale' => 'ar',
		'title' => 'رفع كفاءة التواصل اللحظي:',
		'content' => 'سد الفجوة بين المريض والعيادة من خلال نظام "الشات المباشر". الهدف هو توفير قناة رسمية وسريعة للاستفسارات، مما يقلل الضغط على خطوط الهاتف في العيادة ويمنح المريض إجابات فورية تزيد من ارتباطه بالخدمة.',
	]);

	// item 4
	$aboutUsPageSection3Item4 = $aboutUsPageSection3->items()->create([
		'type' => 'value',
		'slug' => 'value-4',
		'settings' => [],
		'is_active' => true,
		'order' => 4,
	]);

	$aboutUsPageSection3Item4->translations()->create([
		'locale' => 'en',
		'title' => 'Value 4',
		'content' => 'Value 4 description',
	]);
	$aboutUsPageSection3Item4->translations()->create([
		'locale' => 'ar',
		'title' => 'تعزيز الشفافية وضمان الجودة:',
		'content' => 'من خلال نظام التقييم المزدوج (للطبيب والعيادة) ونظام الشكاوى المباشر. يهدف هذا إلى خلق بيئة تنافسية بين العيادات لتقديم أفضل خدمة، وتزويد مديري النظام والعيادات ببيانات حقيقية حول نقاط القوة والضعف لاتخاذ قرارات تطويرية.',
	]);

	// item 5
	$aboutUsPageSection3Item5 = $aboutUsPageSection3->items()->create([
		'type' => 'value',
		'slug' => 'value-5',
		'settings' => [],
		'is_active' => true,
		'order' => 5,
	]);

	$aboutUsPageSection3Item5->translations()->create([
		'locale' => 'en',
		'title' => 'Value 5',
		'content' => 'Value 5 description',
	]);
	$aboutUsPageSection3Item5->translations()->create([
		'locale' => 'ar',
		'title' => 'تمكين مديري العيادات تقنياً:',
		'content' => 'توفير لوحة تحكم شاملة تعمل كـ "مساعد إداري ذكي" لمدير العيادة، تمكنه من مراقبة أداء الكادر الطبي، إدارة الجداول الزمنية، ومتابعة رضا المرضى ومعالجة شكواهم بشكل مؤسسي واحترافي.',
	]);

	// item 6
	$aboutUsPageSection3Item6 = $aboutUsPageSection3->items()->create([
		'type' => 'value',
		'slug' => 'value-6',
		'settings' => [],
		'is_active' => true,
		'order' => 6,
	]);

	$aboutUsPageSection3Item6->translations()->create([
		'locale' => 'en',
		'title' => 'Value 6',
		'content' => 'Value 6 description',
	]);
	$aboutUsPageSection3Item6->translations()->create([
		'locale' => 'ar',
		'title' => 'تحقيق الانتشار والنمو للعيادات المشتركة:',
		'content' => 'يهدف النظام إلى أن يكون "سوقاً طبياً" يجمع العيادات في منصة واحدة، مما يزيد من فرص ظهور العيادات الصغيرة أو الجديدة أمام شريحة أكبر من المرضى، وبالتالي زيادة عدد الحجوزات ونمو العوائد.',
	]);


// --------------- services page data ----------------------

	// services page
	$servicesPage = CmsPage::create([
		'slug' => 'services',
		'name' => 'Services',
		'is_active' => true,
		'order' => 3,
	]);

	// translations
	$servicesPage->translations()->create([
		'locale' => 'en',
		'title' => 'Services',
		'meta_description' => 'Services description',
		'meta_keywords' => 'Services keywords',
	]);
	$servicesPage->translations()->create([
		'locale' => 'ar',
		'title' => 'الخدمات',
		'meta_description' => 'الخدمات الوصف',
		'meta_keywords' => 'الخدمات الكلمات الدلالية',
	]);

	// services page sections
	$servicesPageSection1 = $servicesPage->sections()->create([
		'name' => 'Services',
		'type' => 'services',
		'template' => 'services',
		'settings' => [],
		'is_active' => true,
		'order' => 1,
    	]);

	// translations
	$servicesPageSection1->translations()->create([
		'locale' => 'en',
		'title' => 'Services',
		'subtitle' => 'Our Services',
		'description' => 'Services description',
	]);
	$servicesPageSection1->translations()->create([
		'locale' => 'ar',
		'title' => 'إدارة الحجز الذكي ',
		'subtitle' => 'خدماتنا',
		'description' => 'الخدمات الوصف',
	]);


	// services page section 1 item 1
	$servicesPageSection1Item1 = $servicesPageSection1->items()->create([
		'type' => 'service',
		'slug' => 'service-1',
		'settings' => [],
		'is_active' => true,
		'order' => 1,
	]);
	$servicesPageSection1Item1->translations()->create([
		'locale' => 'en',
		'title' => 'Service 1',
		'content' => 'Service 1 description',
	]);
	$servicesPageSection1Item1->translations()->create([
		'locale' => 'ar',
		'title' => 'إدارة الحجز الذكي ',
		'content' => 'نظام مرن لاختيار الطبيب والوقت المناسب بناء علي ورديات محددة مسبقا ',
	]);



	// services page section 1 item 2
	$servicesPageSection1Item2 = $servicesPageSection1->items()->create([
		'type' => 'service',
		'slug' => 'service-2',
		'settings' => [],
		'is_active' => true,
		'order' => 2,
	]);
	$servicesPageSection1Item2->translations()->create([
		'locale' => 'en',
		'title' => 'Service 2',
		'content' => 'Service 2 description',
	]);
	$servicesPageSection1Item2->translations()->create([
		'locale' => 'ar',
		'title' => 'تحسين تجربة المريض (Patient Centricity): ',
		'content' => 'تسهيل رحلة المريض من خلال توفير منصة واحدة تمكنه من البحث عن العيادات، اختيار الطبيب بناءً على التقييمات، وإتمام الحجز في ثوان',
	]);


	// services page section 1 item 3
	$servicesPageSection1Item3 = $servicesPageSection1->items()->create([
		'type' => 'service',
		'slug' => 'service-3',
		'settings' => [],
		'is_active' => true,
		'order' => 3,
	]);
	$servicesPageSection1Item3->translations()->create([
		'locale' => 'en',
		'title' => 'Service 3',
		'content' => 'Service 3 description',
	]);
	$servicesPageSection1Item3->translations()->create([
		'locale' => 'ar',
		'title' => 'نظام المرافقين القصر',
		'content' => 'إمكانية إضافة حتي ٣ مرافقين من الأبناء (تحت سن ١٨ عام ) لكل حساب مريض لتسهيل حجز العائلة'
	]);


	// services page section 1 item 4
	$servicesPageSection1Item4 = $servicesPageSection1->items()->create([
		'type' => 'service',
		'slug' => 'service-4',
		'settings' => [],
		'is_active' => true,
		'order' => 4,
	]);
	$servicesPageSection1Item4->translations()->create([
		'locale' => 'en',
		'title' => 'Service 4',
		'content' => 'Service 4 description',
	]);
	$servicesPageSection1Item4->translations()->create([
		'locale' => 'ar',
		'title' => 'التواصل الفوري ',
		'content' => 'شات مباشر مع موظف الاستقبال للاستفسارات السريعة وتنسيق الحجوزات',
	]);

	// services page section 1 item 5
	$servicesPageSection1Item5 = $servicesPageSection1->items()->create([
		'type' => 'service',
		'slug' => 'service-5',
		'settings' => [],
		'is_active' => true,
		'order' => 5,
	]);
	$servicesPageSection1Item5->translations()->create([
		'locale' => 'en',
		'title' => 'Service 5',
		'content' => 'Service 5 description',
	]);
	$servicesPageSection1Item5->translations()->create([
		'locale' => 'ar',
		'title' => 'إدارة الشكاوي المتعددة',
		'content' => 'قنوات مخصصه لايصال صوت المريض لمدير العيادة (الخدمات الطبية) او لمدير التطبيق (للمشاكل التقنية ) ',
	]);


	// services page section 1 item 6
	$servicesPageSection1Item6 = $servicesPageSection1->items()->create([
		'type' => 'service',
		'slug' => 'service-6',
		'settings' => [],
		'is_active' => true,
		'order' => 6,
	]);
	$servicesPageSection1Item6->translations()->create([
		'locale' => 'en',
		'title' => 'Service 6',
		'content' => 'Service 6 description',
	]);
	$servicesPageSection1Item6->translations()->create([
		'locale' => 'ar',
		'title' => 'نظام التقييم الشامل',
		'content' => 'تقييم منفصل للأطباء (الجانب الطبي) والعيادات (الجانب التنظيمي ) لضمان جودة الخدمة',
	]);


	// services page section 1 item 7
	$servicesPageSection1Item7 = $servicesPageSection1->items()->create([
		'type' => 'service',
		'slug' => 'service-7',
		'settings' => [],
		'is_active' => true,
		'order' => 7,
	]);
	$servicesPageSection1Item7->translations()->create([
		'locale' => 'en',
		'title' => 'Service 7',
		'content' => 'Service 7 description',
	]);
	$servicesPageSection1Item7->translations()->create([
		'locale' => 'ar',
		'title' => 'لوحات تحكم تخصصية ',
		'content' => 'واجهات مخصصة لكل دور وظيفي لضمان سرعة الإنجاز وسهولة الاستخدام',
	]);


         // services page section 1 item 8
         $servicesPageSection1Item8 = $servicesPageSection1->items()->create([
            'type' => 'service',
            'slug' => 'service-8',
            'settings' => [],
            'is_active' => true,
            'order' => 8,
         ]);
         $servicesPageSection1Item8->translations()->create([
		'locale' => 'en',
		'title' => 'Service 8',
		'content' => 'Service 8 description',
	]);
	$servicesPageSection1Item8->translations()->create([
		'locale' => 'ar',
		'title' => 'تحقيق الانتشار والنمو للعيادات المشتركة',
		'content' => 'يزيد من فرص ظهور العيادات الصغيرة أو الجديدة أمام شريحة أكبر من المرضى، وبالتالي زيادة عدد الحجوزات ونمو العوائد.'
	]);


//------------------------------ faq page --------------------

 $faqPage = CmsPage::create([
        'slug' => 'faq',
        'name' => 'Faq',
        'is_active' => true,
        'order' => 4,
    ]);

    $faqPage->translations()->create([
        'locale' => 'en',
        'title' => 'Faq',
        'meta_description' => 'Faq page',
        'meta_keywords' => 'faq, page',
    ]);

	    $faqPage->translations()->create([
		'locale' => 'ar',
		'title' => 'الأسئلة الشائعة',
		'meta_description' => 'الأسئلة الشائعة',
		'meta_keywords' => 'الأسئلة الشائعة',
	]);

    // faq page sections
    $faqPageSection1 = $faqPage->sections()->create([
        'name' => 'Faq',
        'type' => 'faqs',
        'template' => 'faq',
        'settings' => [],
        'is_active' => true,
        'order' => 1,
    ]);
    // translations
    $faqPageSection1->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions',
        'subtitle' => 'Patient Frequently Ask Questions',
        'description' => '',
    ]);
    $faqPageSection1->translations()->create([
        'locale' => 'ar',
        'title' => 'الأسئلة الشائعة',
        'subtitle' => 'الأسئلة الشائعة للمرضى',
        'description' => '',
    ]);

    // faq page section 1 items
    $faqPageSection1Item1 = $faqPageSection1->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-1',
        'settings' => [],
        'is_active' => true,
        'order' => 1,
    ]);
    $faqPageSection1Item1->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 1',
        'content' => 'Frequently Ask Questions 1 description',
    ]);
    $faqPageSection1Item1->translations()->create([
        'locale' => 'ar',
        'title' => 'كيف يمكنني حجز موعد مع طبيب محدد؟',
        'content' => 'ببساطة، قم بفتح التطبيق، اختر العيادة المطلوبة، ثم تصفح قائمة الأطباء. بعد اختيار الطبيب، ستظهر لك المواعيد المتاحة (الشيفتات)، اختر ما يناسبك واضغط "تأكيد الحجز".',
    ]);

    // faq page section 1 item 2
    $faqPageSection1Item2 = $faqPageSection1->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-2',
        'settings' => [],
        'is_active' => true,
        'order' => 2,
    ]);
    $faqPageSection1Item2->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 2',
        'content' => 'Frequently Ask Questions 2 description',
    ]);
    $faqPageSection1Item2->translations()->create([
        'locale' => 'ar',
        'title' => 'هل يمكنني حجز موعد لأحد أفراد عائلتي؟',
        'content' => 'نعم، يتيح لك النظام إضافة حتى 3 مرافقين (أطفال أو قصر) تحت سن 18 عاماً من خلال ملفك الشخصي، ويمكنك الحجز لهم مباشرة.',
    ]);

    // faq page section 1 item 3
    $faqPageSection1Item3 = $faqPageSection1->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-3',
        'settings' => [],
        'is_active' => true,
        'order' => 3,
    ]);
    $faqPageSection1Item3->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 3',
        'content' => 'Frequently Ask Questions 3 description',
    ]);
    $faqPageSection1Item3->translations()->create([
        'locale' => 'ar',
        'title' => 'كيف أتواصل مع العيادة للاستفسار عن تفاصيل الحجز؟',
        'content' => 'بمجرد إتمام الحجز، يمكنك استخدام ميزة "الشات" داخل التطبيق للتحدث مباشرة مع موظف استقبال العيادة وطرح أي سؤال. وأيضا من خلال العيادة يمكنك ارسال أي رسالة للاستفسار وسيقوم احد موظفي الاستقبال بالرد عليك',
    ]);


    // faq page section 1 item 4
    $faqPageSection1Item4 = $faqPageSection1->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-4',
        'settings' => [],
        'is_active' => true,
        'order' => 4,
    ]);
    $faqPageSection1Item4->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 4',
        'content' => 'Frequently Ask Questions 4 description',
    ]);
    $faqPageSection1Item4->translations()->create([
        'locale' => 'ar',
        'title' => 'ماذا أفعل إذا واجهت مشكلة تقنية في التطبيق؟',
        'content' => 'يمكنك إرسال شكوى مباشرة إلى "مدير التطبيق" من خلال قسم الدعم الفني، وسيقوم فريقنا التقني بمعالجة المشكلة فوراً.',
    ]);


    // faq page section 1 item 5
    $faqPageSection1Item5 = $faqPageSection1->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-5',
        'settings' => [],
        'is_active' => true,
        'order' => 5,
    ]);
    $faqPageSection1Item5->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 5',
        'content' => 'Frequently Ask Questions 5 description',
    ]);
    $faqPageSection1Item5->translations()->create([
        'locale' => 'ar',
        'title' => 'هل يمكنني إلغاء؟',
        'content' => 'نعم، يمكنك إلغاء الموعد من خلال قائمة "حجوزاتي"، مع مراعاة سياسة الإلغاء الخاصة بكل عيادة',
    ]);


    // faq page section 1 item 6
    $faqPageSection1Item6 = $faqPageSection1->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-6',
        'settings' => [],
        'is_active' => true,
        'order' => 6,
    ]);
    $faqPageSection1Item6->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 6',
        'content' => 'Frequently Ask Questions 6 description',
    ]);
    $faqPageSection1Item6->translations()->create([
        'locale' => 'ar',
        'title' => 'متى يمكنني تقييم الطبيب أو العيادة؟',
        'content' => 'يتاح خيار التقييم فور انتهاء الموعد المسجل في النظام، لضمان أن جميع التقييمات نابعة من تجربة حقيقية.',
    ]);


    // faq page section 1 item 7
    $faqPageSection1Item7 = $faqPageSection1->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-7',
        'settings' => [],
        'is_active' => true,
        'order' => 7,
    ]);
    $faqPageSection1Item7->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 7',
        'content' => 'Frequently Ask Questions 7 description',
    ]);
    $faqPageSection1Item7->translations()->create([
        'locale' => 'ar',
        'title' => 'هل معلوماتي الطبية ومحادثاتي آمنة؟',
        'content' => 'بكل تأكيد، نحن نستخدم بروتوكولات تشفير متقدمة لضمان سرية المحادثات بينك وبين العيادة، وحماية كافة بياناتك الشخصية.',
    ]);


    // faq page section 2
    $faqPageSection2 = $faqPage->sections()->create([
        'name' => 'Faq',
        'type' => 'faqs',
        'template' => 'faq',
        'settings' => [],
        'is_active' => true,
        'order' => 2,
    ]);
    // translations
    $faqPageSection2->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions',
        'subtitle' => 'Clinic Frequently Ask Questions',
        'description' => '',
    ]);
    $faqPageSection2->translations()->create([
        'locale' => 'ar',
        'title' => 'الأسئلة الشائعة',
        'subtitle' => 'الأسئلة الشائعة للعيادات',
        'description' => '',
    ]);

    // faq page section 2 item 1
    $faqPageSection2Item1 = $faqPageSection2->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-8',
        'settings' => [],
        'is_active' => true,
        'order' => 1,
    ]);
    $faqPageSection2Item1->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 8',
        'content' => 'Frequently Ask Questions 8 description',
    ]);
    $faqPageSection2Item1->translations()->create([
        'locale' => 'ar',
        'title' => 'كيف يمكن للعيادة الانضمام إلى المنصة؟',
        'content' => 'يمكن للعيادة التسجيل عبر موقعنا الإلكتروني، وإدخال البيانات الأساسية والوثائق المطلوبة، وبعد مراجعة الإدارة يتم تفعيل الحساب فوراً.',
    ]);


    // faq page section 2 item 2
    $faqPageSection2Item2 = $faqPageSection2->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-9',
        'settings' => [],
        'is_active' => true,
        'order' => 2,
    ]);
    $faqPageSection2Item2->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 9',
        'content' => 'Frequently Ask Questions 9 description',
    ]);
    $faqPageSection2Item2->translations()->create([
        'locale' => 'ar',
        'title' => 'كيف يتم تنظيم مواعيد الأطباء؟',
        'content' => 'من خلال لوحة تحكم مدير العيادة، يمكنك إضافة الأطباء وتحديد فترات عملهم (الشيفتات). النظام سيقوم تلقائياً بإظهار هذه المواعيد للمرضى بناءً على التوافر.',
    ]);

    // faq page section 2 item 3
    $faqPageSection2Item3 = $faqPageSection2->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-10',
        'settings' => [],
        'is_active' => true,
        'order' => 3,
    ]);
    $faqPageSection2Item3->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 10',
        'content' => 'Frequently Ask Questions 10 description',
    ]);
    $faqPageSection2Item3->translations()->create([
        'locale' => 'ar',
        'title' => 'من الذي يستلم شكاوى المرضى؟',
        'content' => 'الشكاوى المتعلقة بالخدمة الطبية أو التعامل داخل العيادة تذهب مباشرة إلى "مدير العيادة". أما الشكاوى المتعلقة بالتطبيق فتذهب إلى "إدارة المنصة".'
    ]);


    // faq page section 2 item 4
    $faqPageSection2Item4 = $faqPageSection2->items()->create([
        'type' => 'faqs',
        'slug' => 'faq-11',
        'settings' => [],
        'is_active' => true,
        'order' => 4,
    ]);
    $faqPageSection2Item4->translations()->create([
        'locale' => 'en',
        'title' => 'Frequently Ask Questions 11',
        'content' => 'Frequently Ask Questions 11 description',
    ]);
    $faqPageSection2Item4->translations()->create([
        'locale' => 'ar',
        'title' => 'هل يستطيع موظف الاستقبال إدارة المواعيد؟',
        'content' => 'نعم، يمتلك موظف الاستقبال لوحة تحكم خاصة تمكنه من متابعة الحجوزات اليومية، الرد على استفسارات المرضى عبر الشات، وتنظيم دخول الحالات.',
    ]);


	// subscription page
	$subscriptionPage = CmsPage::create([
		'slug' => 'subscription',
		'name' => 'Subscription',
		'is_active' => true,
		'order' => 5,
	]);

	$subscriptionPage->translations()->create([
		'locale' => 'en',
		'title' => 'Subscription',
		'meta_description' => 'Subscription page',
		'meta_keywords' => 'subscription, page',
	]);

	$subscriptionPage->translations()->create([
		'locale' => 'ar',
		'title' => 'الاشتراك',
		'meta_description' => 'الاشتراك',
		'meta_keywords' => 'الاشتراك',
	]);

// checkout page sections

		// home page about us section
	$subscriptionPageSection1 = $subscriptionPage->sections()->create([
		'name' => 'Subscription',
		'type' => 'subscription',
		'template' => 'subscription',
		'settings' => [],
		'is_active' => true,
		'order' => 4,
	]);

	// translations
	$subscriptionPageSection1->translations()->create([
		'locale' => 'en',
		'title' => 'Subscription',
		'subtitle' => 'Subscription',
		'description' => 'Subscription description',
	]);
	$subscriptionPageSection1->translations()->create([
		'locale' => 'ar',
		'title' => 'أهلا بك شريكا للنجاح',
		'subtitle' => 'اشترك الان',
		'description' => '<p> أنت علي بعد لحظات من منح مرضاك تجربة حجز مميزة. وخطوة واحدة تفصلك عن رقمنة عيادتك والانضمام لنخبة المراكز الطبية الذكية دعنا نبدأ. </p>
		<ul>
			<li> اسم الباقة </li>
			<li> السعر </li>
			<li> دعم فني 24/7 </li>
			<li> عدد غير محدودو من الأطباء </li>
			<li> نظام شات مجاني </li>
		</ul>',
	]);

//-------- end of subscription page data ----------------------
//-------- start of contact page data ----------------------

// contact page
    $contactPage = CmsPage::create([
        'slug' => 'contact',
        'name' => 'Contact',
        'is_active' => true,
        'order' => 6,
    ]);

	// translations
	$contactPage->translations()->create([
		'locale' => 'en',
		'title' => 'Contact',
		'meta_description' => 'Contact page',
		'meta_keywords' => 'contact, page',
	]);

	$contactPage->translations()->create([
		'locale' => 'ar',
		'title' => 'اتصل بنا',
		'meta_description' => 'اتصل بنا',
		'meta_keywords' => 'اتصل بنا',
	]);


// contact page sections
	$contactPageSection1 = $contactPage->sections()->create([
		'name' => 'Contact',
		'type' => 'contact',
		'template' => 'contact',
		'settings' => [],
		'is_active' => true,
		'order' => 1,
	]);

	// translations
	$contactPageSection1->translations()->create([
		'locale' => 'en',
		'title' => 'Contact',
		'subtitle' => 'Contact',
		'description' => 'Contact description',
	]);

	$contactPageSection1->translations()->create([
		'locale' => 'ar',
		'title' => 'اتصل بنا',
		'subtitle' => 'اتصل بنا',
		'description' => 'اتصل بنا',
	]);


    // contact page section 1 items
    $contactPageSection1Item1 = $contactPageSection1->items()->create([
        'type' => 'contact',
        'slug' => 'contact-1',
        'settings' => [],
        'is_active' => true,
        'order' => 1,
    ]);
    $contactPageSection1Item1->translations()->create([
        'locale' => 'en',
        'title' => 'Our Current Location',
        'content' => '4517 Washington Ave. Manchester, Kentucky 39495. USA',
    ]);
    $contactPageSection1Item1->translations()->create([
        'locale' => 'ar',
        'title' => 'موقعنا الحالي',
        'content' => '4517 Washington Ave. Manchester, Kentucky 39495. USA',
    ]);

    // contact page section 1 item 2
    $contactPageSection1Item2 = $contactPageSection1->items()->create([
        'type' => 'contact',
        'slug' => 'contact-2',
        'settings' => [],
        'is_active' => true,
        'order' => 2,
    ]);
    $contactPageSection1Item2->translations()->create([
        'locale' => 'en',
        'title' => 'Phone Number',
        'content' => '+1 (373) 575-6757',
    ]);
    $contactPageSection1Item2->translations()->create([
        'locale' => 'ar',
        'title' => 'رقم الهاتف',
        'content' => '+1 (373) 575-6757',
    ]);

    // contact page section 1 item 3
    $contactPageSection1Item3 = $contactPageSection1->items()->create([
        'type' => 'contact',
        'slug' => 'contact-3',
        'settings' => [],
        'is_active' => true,
        'order' => 3,
    ]);
    $contactPageSection1Item3->translations()->create([
        'locale' => 'en',
        'title' => 'Email Address',
        'content' => 'info@examplemail.edu',
    ]);
    $contactPageSection1Item3->translations()->create([
        'locale' => 'ar',
        'title' => 'البريد الإلكتروني',
        'content' => 'info@examplemail.edu',
    ]);
//-------- end of contact page data ----------------------



    }
}
