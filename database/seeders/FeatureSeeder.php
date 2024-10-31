<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the tables to clear existing data
        DB::table('brand_translations')->truncate();
        DB::table('brands')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $features = [
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en','icon_id'=>'', 'name' => '', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'slug' => '',
                    ],
                    [
                        'locale' => 'ar','icon_id'=>'', 'name' => '', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'slug' => '',
                    ],
                ],
            ],
        ];

        foreach ($features as $feature) {
            $existingBrand = DB::table('features')->where('name',$feature['name'])->first();

            if ($existingBrand) {
                $brandId = $existingBrand->id;
            } else {
                $brandId = DB::table('features')->insertGetId([
                    'is_active' => $feature['is_active'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            foreach ($feature['translations'] as $translation) {
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);
                DB::table('feature_translations')->insert([
                    'feature_id' => $brandId,
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
