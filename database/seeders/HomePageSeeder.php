<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomePageSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the tables to clear existing data
        DB::table('home_translations')->truncate();
        DB::table('homes')->truncate();

        // Re-enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Home Page Data
        $homePage = [
            [
                'page_name' => 'Home Page',
                'hero_header_video_path' => '/videos/hero-banner.mp4',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'hero_header_title' => 'Welcome to Monza Rent Car',
                        'hero_header_subtitle' => 'Experience luxury cars at affordable prices.',

                        'car_only_section_title' => 'Only on Us',
                        'car_only_section_paragraph' => 'Discover exclusive cars available only at Monza Rent Car.',

                        'featured_cars_section_title' => 'Featured Cars',
                        'featured_cars_section_paragraph' => 'Our top picks of premium cars, handpicked for you.',

                        'special_offers_section_title' => 'Special Offers',
                        'special_offers_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',

                        'meta_title' => 'Home Page - Monza Rent Car',
                        'meta_description' => 'Explore our exclusive collection of luxury cars and enjoy special offers.',
                        'meta_keywords' => 'car rental, luxury cars, special offers, rent car',
                        'slug' => 'home-page',
                    ],
                    [
                        'locale' => 'ar',
                        'hero_header_title' => 'مرحباً بكم في مونزا لتأجير السيارات',
                        'hero_header_subtitle' => 'اختبر رفاهية السيارات بأسعار معقولة.',

                        'car_only_section_title' => 'موجود فقط لدينا',
                        'car_only_section_paragraph' => 'اكتشف السيارات الحصرية المتاحة فقط في مونزا لتأجير السيارات.',

                        'featured_cars_section_title' => 'سيارات مميزة',
                        'featured_cars_section_paragraph' => 'أفضل السيارات الفاخرة المختارة خصيصاً لك.',

                        'special_offers_section_title' => 'عروض خاصة',
                        'special_offers_section_paragraph' => 'احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.',

                        'meta_title' => 'الصفحة الرئيسية - مونزا لتأجير السيارات',
                        'meta_description' => 'استكشف مجموعتنا الحصرية من السيارات الفاخرة وتمتع بالعروض الخاصة.',
                        'meta_keywords' => 'تأجير السيارات, سيارات فاخرة, عروض خاصة, استئجار سيارة',
                        'slug' => 'الصفحة-الرئيسية',
                    ],
                ],
            ],
        ];

        // Insert Home Page Data into the `homes` table
        foreach ($homePage as $page) {
            // Insert into main `homes` table and get the inserted ID
            $homePageId = DB::table('homes')->insertGetId([
                'page_name' => $page['page_name'],
                'hero_header_video_path' => $page['hero_header_video_path'],
                'is_active' => $page['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Insert translations into the `home_translations` table
            foreach ($page['translations'] as $translation) {
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);

                DB::table('home_translations')->insert([
                    'home_id' => $homePageId,
                    'locale' => $translation['locale'],
                    'hero_header_title' => $translation['hero_header_title'],
                    'car_only_section_title' => $translation['car_only_section_title'],
                    'car_only_section_paragraph' => $translation['car_only_section_paragraph'],
                    'featured_cars_section_title' => $translation['featured_cars_section_title'],
                    'featured_cars_section_paragraph' => $translation['featured_cars_section_paragraph'],
                    'special_offers_section_title' => $translation['special_offers_section_title'],
                    'special_offers_section_paragraph' => $translation['special_offers_section_paragraph'],
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
