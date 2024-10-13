<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\AboutTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AboutPageSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the tables to clear existing data
        DB::table('about_translations')->truncate();
        DB::table('abouts')->truncate();

        // Re-enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // About Page Data
        $aboutPage = [
            [
                'page_name' => 'About Us',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'about_main_header_title' => 'About Us',
                        'about_main_header_paragraph' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate',
                        'why_choose_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate',
                        'our_vision_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate',
                        'our_mission_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate',
                        'meta_title' => 'About Us',
                        'meta_description' => 'Learn more about our company, our vision, and mission.',
                        'meta_keywords' => 'about us, company, vision, mission',
                        'slug' => 'about-us',
                    ],
                    [
                        'locale' => 'ar',
                        'about_main_header_title' => 'معلومات عنا',
                        'about_main_header_paragraph' => 'لوريم إيبسوم هو نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر',
                        'why_choose_content' => 'لوريم إيبسوم هو نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر',
                        'our_vision_content' => 'لوريم إيبسوم هو نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر',
                        'our_mission_content' => 'لوريم إيبسوم هو نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر',
                        'meta_title' => 'معلومات عنا',
                        'meta_description' => 'تعرف على المزيد عن شركتنا ورؤيتنا ورسالتنا.',
                        'meta_keywords' => 'معلومات عنا, شركة, رؤية, رسالة',
                        'slug' => 'معلومات-عنا',
                    ],
                ],
            ],
        ];

        // Insert About Page Data into the `about_pages` table
        foreach ($aboutPage as $page) {
            // Insert into main `about_pages` table and get the inserted ID
            $aboutPageId = DB::table('abouts')->insertGetId([
                'page_name' => $page['page_name'],
                'is_active' => $page['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Insert translations into the `about_page_translations` table
            foreach ($page['translations'] as $translation) {
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);
                DB::table('about_translations')->insert([
                    'about_id' => $aboutPageId,
                    'locale' => $translation['locale'],
                    'about_main_header_title' => $translation['about_main_header_title'],
                    'about_main_header_paragraph' => $translation['about_main_header_paragraph'],
                    'why_choose_content' => $translation['why_choose_content'],
                    'our_vision_content' => $translation['our_vision_content'],
                    'our_mission_content' => $translation['our_mission_content'],
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
