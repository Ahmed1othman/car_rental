<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GearTypeSeeder extends Seeder
{
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the tables to clear existing data
        DB::table('gear_type_translations')->truncate();
        DB::table('gear_types')->truncate();

        // Re-enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // List of common gear types
        $gearTypes = [
            [
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Automatic',
                        'meta_title' => 'Automatic Gear Type',
                        'meta_description' => 'Find cars with automatic gear type for easy and comfortable driving.',
                        'meta_keywords' => 'Automatic, Gear Type, Car Rental, Dubai, UAE',
                        'slug' => 'automatic',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أوتوماتيك',
                        'meta_title' => 'نوع الجير الأوتوماتيكي',
                        'meta_description' => 'ابحث عن سيارات بنوع جير أوتوماتيكي للقيادة المريحة والسهلة.',
                        'meta_keywords' => 'أوتوماتيك, نوع الجير, تأجير سيارات, دبي, الإمارات',
                        'slug' => 'أوتوماتيك',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Manual',
                        'meta_title' => 'Manual Gear Type',
                        'meta_description' => 'Find cars with manual gear type for full control over your driving experience.',
                        'meta_keywords' => 'Manual, Gear Type, Car Rental, Dubai, UAE',
                        'slug' => 'manual',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'يدوي',
                        'meta_title' => 'نوع الجير اليدوي',
                        'meta_description' => 'ابحث عن سيارات بنوع جير يدوي للتحكم الكامل في تجربة القيادة الخاصة بك.',
                        'meta_keywords' => 'يدوي, نوع الجير, تأجير سيارات, دبي, الإمارات',
                        'slug' => 'يدوي',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Semi-Automatic',
                        'meta_title' => 'Semi-Automatic Gear Type',
                        'meta_description' => 'Find cars with semi-automatic gear type, a blend of automatic and manual.',
                        'meta_keywords' => 'Semi-Automatic, Gear Type, Car Rental, Dubai, UAE',
                        'slug' => 'semi-automatic',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'نصف أوتوماتيك',
                        'meta_title' => 'نوع الجير نصف أوتوماتيكي',
                        'meta_description' => 'ابحث عن سيارات بنوع جير نصف أوتوماتيكي، يجمع بين الجير الأوتوماتيكي واليدوي.',
                        'meta_keywords' => 'نصف أوتوماتيك, نوع الجير, تأجير سيارات, دبي, الإمارات',
                        'slug' => 'نصف-أوتوماتيك',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'CVT',
                        'meta_title' => 'CVT Gear Type',
                        'meta_description' => 'Find cars with CVT (Continuously Variable Transmission) for smoother driving.',
                        'meta_keywords' => 'CVT, Gear Type, Car Rental, Dubai, UAE',
                        'slug' => 'cvt',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'سي تي ف',
                        'meta_title' => 'نوع الجير CVT',
                        'meta_description' => 'ابحث عن سيارات بنوع جير CVT (ناقل الحركة المتغير باستمرار) لتجربة قيادة أكثر سلاسة.',
                        'meta_keywords' => 'CVT, نوع الجير, تأجير سيارات, دبي, الإمارات',
                        'slug' => 'سي تي ف',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Dual-Clutch',
                        'meta_title' => 'Dual-Clutch Gear Type',
                        'meta_description' => 'Find cars with Dual-Clutch gear type for seamless shifting.',
                        'meta_keywords' => 'Dual-Clutch, Gear Type, Car Rental, Dubai, UAE',
                        'slug' => 'dual-clutch',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'قابض مزدوج',
                        'meta_title' => 'نوع الجير القابض المزدوج',
                        'meta_description' => 'ابحث عن سيارات بنوع جير القابض المزدوج لتغيير التروس بسلاسة.',
                        'meta_keywords' => 'قابض مزدوج, نوع الجير, تأجير سيارات, دبي, الإمارات',
                        'slug' => 'قابض-مزدوج',
                    ],
                ],
            ],
        ];

        foreach ($gearTypes as $type) {
            // Insert into gear_types table
            $gearTypeId = DB::table('gear_types')->insertGetId([
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($type['translations'] as $translation) {
                // Split keywords and store as JSON in the format [{"value":"keyword1"},{"value":"keyword2"}]
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);

                // Insert translations into gear_type_translations table
                DB::table('gear_type_translations')->insert([
                    'gear_type_id' => $gearTypeId,
                    'locale' => $translation['locale'],
                    'name' => $translation['name'],
                    'meta_title' => $translation['meta_title'],
                    'meta_description' => $translation['meta_description'],
                    'meta_keywords' => json_encode($metaKeywords),
                    'slug' => $translation['slug'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
