<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogCategoryTranslation;
use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use App\Models\SeoMeta;
use App\Models\SeoMetaTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $this->clearBlogTables();

        $categories = $this->seedCategories();
        $this->seedPosts($categories);
    }

    private function clearBlogTables(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            $postIds = BlogPost::withTrashed()->pluck('id');
            $categoryIds = BlogCategory::withTrashed()->pluck('id');

            if ($postIds->isNotEmpty()) {
                $seoIds = SeoMeta::query()
                    ->where('seoable_type', BlogPost::class)
                    ->whereIn('seoable_id', $postIds)
                    ->pluck('id');

                if ($seoIds->isNotEmpty()) {
                    SeoMetaTranslation::query()->whereIn('seo_meta_id', $seoIds)->delete();
                    SeoMeta::query()->whereIn('id', $seoIds)->delete();
                }

                Media::query()
                    ->where('model_type', BlogPost::class)
                    ->whereIn('model_id', $postIds)
                    ->delete();
            }

            if ($categoryIds->isNotEmpty()) {
                Media::query()
                    ->where('model_type', BlogCategory::class)
                    ->whereIn('model_id', $categoryIds)
                    ->delete();
            }

            DB::table('blog_category_post')->truncate();
            BlogPostTranslation::query()->truncate();
            BlogCategoryTranslation::query()->truncate();
            BlogPost::query()->truncate();
            BlogCategory::query()->truncate();
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * @return array<string, BlogCategory>
     */
    private function seedCategories(): array
    {
        $items = [
            [
                'name' => 'Preventive Care',
                'slug' => 'preventive-care',
                'en' => [
                    'title' => 'Preventive Care',
                    'description' => 'Tips and guidance on screenings, vaccinations, and healthy lifestyle habits that help prevent illness before it starts.',
                ],
                'ar' => [
                    'title' => 'الرعاية الوقائية',
                    'description' => 'نصائح وإرشادات حول الفحوصات والتطعيمات والعادات الصحية التي تساعد على الوقاية من الأمراض قبل حدوثها.',
                ],
            ],
            [
                'name' => 'Cardiology',
                'slug' => 'cardiology',
                'en' => [
                    'title' => 'Cardiology',
                    'description' => 'Articles about heart health, blood pressure, cholesterol, and modern cardiovascular treatments.',
                ],
                'ar' => [
                    'title' => 'أمراض القلب',
                    'description' => 'مقالات حول صحة القلب وضغط الدم والكوليسترول وأحدث علاجات أمراض القلب والأوعية الدموية.',
                ],
            ],
            [
                'name' => 'Pediatrics',
                'slug' => 'pediatrics',
                'en' => [
                    'title' => 'Pediatrics',
                    'description' => 'Trusted advice for parents on child growth, nutrition, immunizations, and common childhood conditions.',
                ],
                'ar' => [
                    'title' => 'طب الأطفال',
                    'description' => 'نصائح موثوقة للآباء حول نمو الأطفال والتغذية والتطعيمات والحالات الشائعة في مرحلة الطفولة.',
                ],
            ],
            [
                'name' => 'Mental Wellness',
                'slug' => 'mental-wellness',
                'en' => [
                    'title' => 'Mental Wellness',
                    'description' => 'Practical insights on stress management, sleep, anxiety, and maintaining emotional well-being.',
                ],
                'ar' => [
                    'title' => 'الصحة النفسية',
                    'description' => 'رؤى عملية حول إدارة التوتر والنوم والقلق والحفاظ على التوازن العاطفي والصحة النفسية.',
                ],
            ],
            [
                'name' => 'Nutrition & Lifestyle',
                'slug' => 'nutrition-lifestyle',
                'en' => [
                    'title' => 'Nutrition & Lifestyle',
                    'description' => 'Evidence-based nutrition guidance and lifestyle changes that support long-term health.',
                ],
                'ar' => [
                    'title' => 'التغذية ونمط الحياة',
                    'description' => 'إرشادات غذائية مبنية على الأدلة وتغييرات في نمط الحياة تدعم الصحة على المدى الطويل.',
                ],
            ],
        ];

        $map = [];

        foreach ($items as $index => $item) {
            $category = BlogCategory::create([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'is_active' => true,
            ]);

            foreach (['en', 'ar'] as $locale) {
                $category->translations()->create([
                    'locale' => $locale,
                    'title' => $item[$locale]['title'],
                    'description' => $item[$locale]['description'],
                ]);
            }

            $map[$item['slug']] = $category;
        }

        return $map;
    }

    /**
     * @param  array<string, BlogCategory>  $categories
     */
    private function seedPosts(array $categories): void
    {
        $posts = [
            [
                'name' => 'Annual Health Screenings',
                'slug' => 'annual-health-screenings-you-shouldnt-skip',
                'publish_date' => now()->subDays(20),
                'categories' => ['preventive-care', 'cardiology'],
                'en' => [
                    'title' => 'Annual Health Screenings You Shouldn’t Skip',
                    'summary' => 'A practical checklist of essential medical screenings that help detect health issues early and protect your long-term well-being.',
                    'content' => '<p>Preventive screenings are one of the most effective ways to stay healthy. Many serious conditions develop silently, and early detection can make treatment simpler and more successful.</p><p>Adults should discuss blood pressure checks, cholesterol panels, blood glucose testing, and cancer screenings with their physician based on age and family history.</p><h3>Why screenings matter</h3><p>Regular check-ups help your care team identify risks before symptoms appear, update vaccinations, and personalize lifestyle recommendations for your heart, metabolism, and overall wellness.</p>',
                    'tags' => ['screening', 'prevention', 'checkup'],
                ],
                'ar' => [
                    'title' => 'فحوصات صحية سنوية لا يجب تخطيها',
                    'summary' => 'قائمة عملية بأهم الفحوصات الطبية التي تساعد على اكتشاف المشاكل الصحية مبكرًا والحفاظ على صحتك على المدى الطويل.',
                    'content' => '<p>تُعد الفحوصات الوقائية من أكثر الطرق فعالية للحفاظ على الصحة. كثير من الأمراض الخطيرة تتطور بصمت، والاكتشاف المبكر يجعل العلاج أبسط وأكثر نجاحًا.</p><p>ينبغي للبالغين مناقشة فحوصات ضغط الدم والكوليسترول والسكر وفحوصات السرطان مع الطبيب وفق العمر والتاريخ العائلي.</p><h3>لماذا الفحوصات مهمة؟</h3><p>تساعد المتابعة المنتظمة فريق الرعاية على اكتشاف المخاطر قبل ظهور الأعراض، وتحديث التطعيمات، وتخصيص توصيات نمط الحياة لصحة القلب والأيض والعافية العامة.</p>',
                    'tags' => ['فحوصات', 'وقاية', 'متابعة'],
                ],
            ],
            [
                'name' => 'Heart-Healthy Habits',
                'slug' => 'simple-habits-for-a-healthier-heart',
                'publish_date' => now()->subDays(16),
                'categories' => ['cardiology', 'nutrition-lifestyle'],
                'en' => [
                    'title' => 'Simple Habits for a Healthier Heart',
                    'summary' => 'Small daily choices—movement, sleep, and balanced meals—can significantly reduce cardiovascular risk over time.',
                    'content' => '<p>Heart disease remains a leading health concern worldwide, but many risk factors are modifiable. Walking for 30 minutes most days, reducing salt intake, and managing stress all support better cardiovascular outcomes.</p><p>If you have high blood pressure or a family history of heart disease, ask your doctor about personalized targets for blood pressure, cholesterol, and physical activity.</p><blockquote><p>Consistent habits matter more than perfect routines. Start with one sustainable change and build from there.</p></blockquote>',
                    'tags' => ['heart', 'blood-pressure', 'exercise'],
                ],
                'ar' => [
                    'title' => 'عادات بسيطة لقلب أكثر صحة',
                    'summary' => 'خيارات يومية بسيطة مثل الحركة والنوم والوجبات المتوازنة يمكن أن تقلل مخاطر أمراض القلب بشكل كبير مع الوقت.',
                    'content' => '<p>تبقى أمراض القلب من أبرز التحديات الصحية عالميًا، لكن كثيرًا من عوامل الخطر قابلة للتعديل. المشي 30 دقيقة معظم أيام الأسبوع، وتقليل الملح، وإدارة التوتر كلها تدعم صحة القلب.</p><p>إذا كنت تعاني من ارتفاع ضغط الدم أو لديك تاريخ عائلي لأمراض القلب، فاستشر طبيبك حول الأهداف المناسبة لضغط الدم والكوليسترول والنشاط البدني.</p><blockquote><p>العادات المنتظمة أهم من الروتين المثالي. ابدأ بتغيير واحد قابل للاستمرار ثم ابنِ عليه.</p></blockquote>',
                    'tags' => ['قلب', 'ضغط-الدم', 'رياضة'],
                ],
            ],
            [
                'name' => 'Child Vaccination Guide',
                'slug' => 'childhood-vaccination-guide-for-parents',
                'publish_date' => now()->subDays(12),
                'categories' => ['pediatrics', 'preventive-care'],
                'en' => [
                    'title' => 'Childhood Vaccination Guide for Parents',
                    'summary' => 'Understand why childhood immunizations matter and how to keep your child’s vaccine schedule on track.',
                    'content' => '<p>Vaccines protect children from serious and sometimes life-threatening illnesses. Following the recommended immunization schedule helps build immunity at the right stages of growth.</p><p>Bring your child’s vaccination card to every visit, and ask your pediatrician about catch-up doses if any appointments were missed. Mild fever or soreness after a shot is common and usually temporary.</p><h3>Talk with your pediatrician</h3><p>Every child is unique. Your doctor can explain timing, possible side effects, and any special considerations related to allergies or medical history.</p>',
                    'tags' => ['vaccines', 'children', 'pediatrics'],
                ],
                'ar' => [
                    'title' => 'دليل تطعيمات الأطفال للآباء',
                    'summary' => 'تعرّف على أهمية تطعيمات الأطفال وكيف تحافظ على جدول اللقاحات في موعده.',
                    'content' => '<p>تحمي اللقاحات الأطفال من أمراض خطيرة وقد تكون مهددة للحياة. اتباع جدول التطعيمات الموصى به يساعد على بناء المناعة في المراحل المناسبة من النمو.</p><p>احرص على إحضار بطاقة التطعيم في كل زيارة، واسأل طبيب الأطفال عن الجرعات التعويضية إذا فات موعد. الحمى الخفيفة أو الألم بعد الحقنة أمر شائع ومؤقت عادة.</p><h3>تحدث مع طبيب الأطفال</h3><p>كل طفل حالة خاصة. يمكن لطبيبك شرح التوقيت والآثار الجانبية المحتملة وأي اعتبارات متعلقة بالحساسية أو التاريخ الطبي.</p>',
                    'tags' => ['تطعيمات', 'أطفال', 'وقاية'],
                ],
            ],
            [
                'name' => 'Managing Daily Stress',
                'slug' => 'managing-daily-stress-for-better-health',
                'publish_date' => now()->subDays(8),
                'categories' => ['mental-wellness'],
                'en' => [
                    'title' => 'Managing Daily Stress for Better Health',
                    'summary' => 'Chronic stress affects sleep, immunity, and heart health. Learn practical ways to restore balance in everyday life.',
                    'content' => '<p>Stress is a normal response to life’s demands, but when it becomes constant it can raise blood pressure, disrupt sleep, and weaken immunity.</p><p>Simple techniques such as deep breathing, short walks, journaling, and setting digital boundaries can lower stress levels. If anxiety or low mood persists, speaking with a healthcare professional is an important next step.</p><p>Mental wellness is part of whole-person care—just as important as physical check-ups.</p>',
                    'tags' => ['stress', 'mental-health', 'wellness'],
                ],
                'ar' => [
                    'title' => 'إدارة التوتر اليومي لصحة أفضل',
                    'summary' => 'التوتر المزمن يؤثر على النوم والمناعة وصحة القلب. تعرّف على طرق عملية لاستعادة التوازن في الحياة اليومية.',
                    'content' => '<p>التوتر استجابة طبيعية لتحديات الحياة، لكنه عندما يصبح مستمرًا قد يرفع ضغط الدم ويؤثر على النوم ويضعف المناعة.</p><p>تقنيات بسيطة مثل التنفس العميق والمشي القصير وتدوين الأفكار ووضع حدود لاستخدام الشاشات يمكن أن تخفف التوتر. وإذا استمر القلق أو انخفاض المزاج، فالتحدث مع مختص صحي خطوة مهمة.</p><p>الصحة النفسية جزء من الرعاية الشاملة—ولا تقل أهمية عن الفحوصات الجسدية.</p>',
                    'tags' => ['توتر', 'صحة-نفسية', 'عافية'],
                ],
            ],
            [
                'name' => 'Balanced Plate Guide',
                'slug' => 'building-a-balanced-plate-for-everyday-energy',
                'publish_date' => now()->subDays(5),
                'categories' => ['nutrition-lifestyle', 'preventive-care'],
                'en' => [
                    'title' => 'Building a Balanced Plate for Everyday Energy',
                    'summary' => 'Learn how to combine protein, fiber, and healthy fats in meals that support energy, digestion, and long-term health.',
                    'content' => '<p>A balanced plate does not require complicated recipes. Aim for colorful vegetables, lean proteins, whole grains, and healthy fats at most meals.</p><p>Hydration matters too—water supports digestion, concentration, and physical performance. Reducing sugary drinks and ultra-processed snacks can improve energy levels within weeks.</p><h3>Start small</h3><p>Swap refined grains for whole grains, add one extra serving of vegetables, and prepare simple home meals a few times each week.</p>',
                    'tags' => ['nutrition', 'diet', 'energy'],
                ],
                'ar' => [
                    'title' => 'بناء طبق متوازن لطاقة يومية أفضل',
                    'summary' => 'تعرّف على كيفية الجمع بين البروتين والألياف والدهون الصحية في وجبات تدعم الطاقة والهضم والصحة طويلة الأمد.',
                    'content' => '<p>الطبق المتوازن لا يحتاج وصفات معقدة. احرص على الخضروات الملونة والبروتينات قليلة الدهون والحبوب الكاملة والدهون الصحية في معظم الوجبات.</p><p>الترطيب مهم أيضًا—الماء يدعم الهضم والتركيز والأداء البدني. وتقليل المشروبات السكرية والوجبات فائقة المعالجة يمكن أن يحسن مستويات الطاقة خلال أسابيع.</p><h3>ابدأ بخطوات صغيرة</h3><p>استبدل الحبوب المكررة بالحبوب الكاملة، وأضف حصة إضافية من الخضروات، وحضّر وجبات منزلية بسيطة عدة مرات أسبوعيًا.</p>',
                    'tags' => ['تغذية', 'نظام-غذائي', 'طاقة'],
                ],
            ],
            [
                'name' => 'Choosing Primary Care',
                'slug' => 'how-to-choose-the-right-primary-care-doctor',
                'publish_date' => now()->subDays(2),
                'categories' => ['preventive-care', 'pediatrics'],
                'en' => [
                    'title' => 'How to Choose the Right Primary Care Doctor',
                    'summary' => 'Finding the right physician builds trust and continuity of care for you and your family across every stage of life.',
                    'content' => '<p>Your primary care doctor is often the first point of contact for check-ups, chronic disease follow-up, and referrals to specialists.</p><p>Look for clear communication, convenient location or telehealth options, and experience with conditions relevant to your family. Ask about appointment availability, after-hours support, and how test results are shared.</p><p>A strong doctor–patient relationship helps you stay proactive about prevention and makes care smoother when illness occurs.</p>',
                    'tags' => ['primary-care', 'doctors', 'clinic'],
                ],
                'ar' => [
                    'title' => 'كيف تختار طبيب الرعاية الأولية المناسب',
                    'summary' => 'اختيار الطبيب المناسب يبني الثقة واستمرارية الرعاية لك ولأسرتك عبر مراحل الحياة المختلفة.',
                    'content' => '<p>طبيب الرعاية الأولية غالبًا هو نقطة التواصل الأولى للفحوصات ومتابعة الأمراض المزمنة والإحالة إلى التخصصات.</p><p>ابحث عن تواصل واضح وموقع مناسب أو خيارات طب عن بُعد، وخبرة في الحالات المتعلقة بأسرتك. واسأل عن مواعيد المواعيد والدعم خارج أوقات العمل وكيفية مشاركة نتائج الفحوصات.</p><p>علاقة قوية بين الطبيب والمريض تساعدك على الوقاية بشكل استباقي وتسهّل الرعاية عند المرض.</p>',
                    'tags' => ['رعاية-أولية', 'أطباء', 'عيادة'],
                ],
            ],
            [
                'name' => 'Sleep and Recovery',
                'slug' => 'why-quality-sleep-is-essential-for-recovery',
                'publish_date' => now()->subDay(),
                'categories' => ['mental-wellness', 'nutrition-lifestyle'],
                'en' => [
                    'title' => 'Why Quality Sleep Is Essential for Recovery',
                    'summary' => 'Good sleep strengthens immunity, supports mental clarity, and helps the body repair after illness or physical stress.',
                    'content' => '<p>Sleep is a critical pillar of health. During deep sleep, the body repairs tissues, consolidates memory, and regulates hormones that affect appetite and mood.</p><p>Adults generally need 7–9 hours of quality sleep. Consistent bedtimes, limiting caffeine late in the day, and reducing screen exposure before bed can improve sleep quality.</p><p>If snoring, insomnia, or daytime fatigue continues, consult a clinician—sleep disorders are treatable and often overlooked.</p>',
                    'tags' => ['sleep', 'recovery', 'immunity'],
                ],
                'ar' => [
                    'title' => 'لماذا يُعد النوم الجيد أساسيًا للتعافي',
                    'summary' => 'النوم الجيد يقوي المناعة ويدعم صفو الذهن ويساعد الجسم على الإصلاح بعد المرض أو الإجهاد البدني.',
                    'content' => '<p>النوم ركيزة أساسية للصحة. خلال النوم العميق يصلح الجسم الأنسجة ويثبّت الذاكرة وينظم هرمونات تؤثر على الشهية والمزاج.</p><p>يحتاج معظم البالغين إلى 7–9 ساعات من النوم الجيد. مواعيد نوم ثابتة وتقليل الكافيين مساءً وتقليل الشاشات قبل النوم يمكن أن تحسن جودة النوم.</p><p>إذا استمر الشخير أو الأرق أو الإرهاق أثناء النهار، فاستشر مختصًا—اضطرابات النوم قابلة للعلاج وغالبًا ما تُهمل.</p>',
                    'tags' => ['نوم', 'تعافي', 'مناعة'],
                ],
            ],
            [
                'name' => 'Diabetes Risk Signs',
                'slug' => 'early-warning-signs-of-diabetes-risk',
                'publish_date' => now(),
                'categories' => ['preventive-care', 'nutrition-lifestyle', 'cardiology'],
                'en' => [
                    'title' => 'Early Warning Signs of Diabetes Risk',
                    'summary' => 'Recognize common warning signs and learn how lifestyle changes and medical follow-up can lower diabetes risk.',
                    'content' => '<p>Increased thirst, frequent urination, unexplained fatigue, and slow-healing wounds can be early clues of blood sugar problems.</p><p>Risk is higher with excess weight, sedentary habits, and a family history of diabetes. A simple fasting glucose or HbA1c test can clarify your status.</p><h3>What you can do</h3><p>Focus on balanced meals, regular activity, and routine check-ups. Early action can delay or prevent type 2 diabetes and protect your heart and kidneys.</p>',
                    'tags' => ['diabetes', 'blood-sugar', 'prevention'],
                ],
                'ar' => [
                    'title' => 'علامات مبكرة لخطر الإصابة بالسكري',
                    'summary' => 'تعرّف على العلامات التحذيرية الشائعة وكيف يمكن لتغيير نمط الحياة والمتابعة الطبية أن يقللا خطر السكري.',
                    'content' => '<p>العطش الزائد وكثرة التبول والإرهاق غير المبرر وبطء التئام الجروح قد تكون مؤشرات مبكرة لمشاكل سكر الدم.</p><p>يزداد الخطر مع زيادة الوزن وقلة الحركة ووجود تاريخ عائلي للسكري. فحص سكر الصائم أو التراكمي يمكن أن يوضح حالتك.</p><h3>ماذا يمكنك أن تفعل؟</h3><p>ركز على وجبات متوازنة ونشاط منتظم وفحوصات دورية. التدخل المبكر قد يؤخر أو يمنع السكري من النوع الثاني ويحمي القلب والكلى.</p>',
                    'tags' => ['سكري', 'سكر-الدم', 'وقاية'],
                ],
            ],
        ];

        foreach ($posts as $item) {
            $post = BlogPost::create([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'is_active' => true,
                'publish_date' => $item['publish_date'],
            ]);

            foreach (['en', 'ar'] as $locale) {
                $post->translations()->create([
                    'locale' => $locale,
                    'title' => $item[$locale]['title'],
                    'summary' => $item[$locale]['summary'],
                    'content' => $item[$locale]['content'],
                    'tags' => $item[$locale]['tags'],
                ]);
            }

            $categoryIds = collect($item['categories'])
                ->map(fn ($slug) => $categories[$slug]->id ?? null)
                ->filter()
                ->values()
                ->all();

            $post->categories()->sync($categoryIds);
        }
    }
}
