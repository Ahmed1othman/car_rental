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
                        'hero_header_title' => 'Welcome to Afandina Rent Car',
                        'hero_header_subtitle' => 'Experience luxury cars at affordable prices.',

                        'car_only_section_title' => 'Only on Us',
                        'car_only_section_paragraph' => 'Discover exclusive cars available only at Afandina Rent Car.',

                        'featured_cars_section_title' => 'Featured Cars',
                        'featured_cars_section_paragraph' => 'Our top picks of premium cars, handpicked for you.',

                        'special_offers_section_title' => 'Special Offers',
                        'special_offers_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',

                        'why_choose_us_section_title' => 'Why Choose Us',
                        'why_choose_us_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',

                        'faq_section_title' => 'FAQ Section',
                        'faq_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',


                        'where_find_us_section_title' => 'Where to Find Us',
                        'where_find_us_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',

                        'required_documents_section_title' => 'Special Offers',
                        'required_documents_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',

                        'instagram_section_title' => 'Instagram Section',
                        'instagram_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',

                        'category_section_title' => 'Category Section',
                        'category_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',

                        'brand_section_title' => 'Brands Section',
                        'brand_section_paragraph' => 'Get the best deals and special offers on your favorite cars.',

                        'blog_section_title' => 'Contact With Our Team',
                        'blog_section_paragraph' => 'Lorem ipsum dolor sit amet consectetur. Aliquam a velit neque cursus quis. Nisi sed ut pharetra nunc ultrices viverra habitant blandit lobortis. Diam non risus ut cras sed. Enim lectus risus ',


                        'contact_us_title' => 'Contact With Our Team',
                        'contact_us_paragraph' => 'Lorem ipsum dolor sit amet consectetur. Aliquam a velit neque cursus quis. Nisi sed ut pharetra nunc ultrices viverra habitant blandit lobortis. Diam non risus ut cras sed. Enim lectus risus ',

                        'contact_us_detail_title' => 'Contact Details',
                        'contact_us_detail_paragraph' => 'Lorem ipsum dolor sit amet consectetur. Aliquam a velit neque cursus quis. Nisi sed ut pharetra nunc ultrices viverra habitant b.',


                        'meta_title' => 'Home Page - Afandina Rent Car',
                        'meta_description' => 'Explore our exclusive collection of luxury cars and enjoy special offers.',
                        'meta_keywords' => 'car rental, luxury cars, special offers, rent car',
                        'slug' => 'home-page',
                    ],
                    [
                        'locale' => 'ar',
                        'hero_header_title' => 'مرحباً بكم في أفندينا لتأجير السيارات',
                        'hero_header_subtitle' => 'اختبر رفاهية السيارات بأسعار معقولة.',

                        'car_only_section_title' => 'موجود فقط لدينا',
                        'car_only_section_paragraph' => 'اكتشف السيارات الحصرية المتاحة فقط في أفندينا لتأجير السيارات.',

                        'featured_cars_section_title' => 'سيارات مميزة',
                        'featured_cars_section_paragraph' => 'أفضل السيارات الفاخرة المختارة خصيصاً لك.',

                        'special_offers_section_title' => 'عروض خاصة',
                        'special_offers_section_paragraph' => 'احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.',

                        'why_choose_us_section_title' => 'من يتم الاستخدام',
                        'why_choose_us_section_paragraph' => 'احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.',

                        'faq_section_title' => 'الاسئلة المتكررة',
                        'faq_section_paragraph' => 'احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.  ',


                        'where_find_us_section_title' => 'موجود فقط في افندينا لتأجير السيارات',
                        'where_find_us_section_paragraph' => ' احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.',

                        'required_documents_section_title' => 'عروض خاصة',
                        'required_documents_section_paragraph' => 'احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.',

                        'instagram_section_title' => 'افندينا علي انستحرام',
                        'instagram_section_paragraph' => 'احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.',

                        'category_section_title' => 'فئات السيارات',
                        'category_section_paragraph' => 'احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.',

                        'brand_section_title' => 'براندات السيارات',
                        'brand_section_paragraph' =>  'احصل على أفضل الصفقات والعروض الخاصة على سياراتك المفضلة.',

                        'blog_section_title' => 'المقالات والاخبار',
                        'blog_section_paragraph' => 'نص لوريم ايباد يتواصل معنا بالرسائل التالية. يرجى ادخال البيانات التالية لتواص للرسائل التالية.  ',

                        'contact_us_title' => 'تواصل معنا',
                        'contact_us_paragraph' => 'لوريم ايباد يتواصل معنا بالرسائل التالية. يرجى ادخال البيانات التالية لتواص للرسائل التالية.  ',
                        'contact_us_detail_title' => 'تفاصيل التواصل',
                        'contact_us_detail_paragraph' => 'لوريم نص يتواصل معنا بالرسائل التالية. يرجى ادخال البيانات التالية لتواص للرس� التالية.  ',


                        'meta_title' => 'الصفحة الرئيسية - أفندينا لتأجير السيارات',
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
                    'why_choose_us_section_title' => $translation['why_choose_us_section_title'],
                    'why_choose_us_section_paragraph' => $translation['why_choose_us_section_paragraph'],
                    'faq_section_title' => $translation['faq_section_title'],
                    'faq_section_paragraph' => $translation['faq_section_paragraph'],
                    'where_find_us_section_title' => $translation['where_find_us_section_title'],
                    'where_find_us_section_paragraph' => $translation['where_find_us_section_paragraph'],
                   'required_documents_section_title' => $translation['required_documents_section_title'],
                   'required_documents_section_paragraph' => $translation['required_documents_section_paragraph'],
                    'instagram_section_title' => $translation['instagram_section_title'],
                    'instagram_section_paragraph' => $translation['instagram_section_paragraph'],
                    'category_section_title' => $translation['category_section_title'],
                    'category_section_paragraph' => $translation['category_section_paragraph'],
                    'brand_section_title' => $translation['brand_section_title'],
                    'brand_section_paragraph' => $translation['brand_section_paragraph'],
                    'blog_section_title' => $translation['blog_section_title'],
                    'blog_section_paragraph' => $translation['blog_section_paragraph'],
                    'contact_us_title' => $translation['contact_us_title'],
                    'contact_us_paragraph' => $translation['contact_us_paragraph'],
                    'contact_us_detail_title' => $translation['contact_us_detail_title'],
                    'contact_us_detail_paragraph' => $translation['contact_us_detail_paragraph'],
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
