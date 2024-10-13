<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarModelSeeder extends Seeder
{
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the tables to clear existing data
        DB::table('car_model_translations')->truncate();
        DB::table('car_models')->truncate();

        // Re-enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // List of car models with their translations
        $carModels = [
            [
                'brand_id' => 1, // Assuming brand_id 1 is for 'Toyota'
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Camry',
                        'meta_title' => 'Toyota Camry',
                        'meta_description' => 'Toyota Camry is a popular sedan model with excellent reliability.',
                        'meta_keywords' => 'Toyota Camry, sedan, reliable car, car rental Dubai',
                        'slug' => 'toyota-camry',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'كامري',
                        'meta_title' => 'تويوتا كامري',
                        'meta_description' => 'تويوتا كامري هي سيارة سيدان مشهورة تتمتع بمصداقية ممتازة.',
                        'meta_keywords' => 'تويوتا كامري, سيدان, سيارة موثوقة, تأجير سيارات دبي',
                        'slug' => 'تويوتا-كامري',
                    ],
                ]
            ],
            [
                'brand_id' => 2, // Assuming brand_id 2 is for 'BMW'
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'X5',
                        'meta_title' => 'BMW X5',
                        'meta_description' => 'BMW X5 is a luxury SUV with sporty handling and high performance.',
                        'meta_keywords' => 'BMW X5, SUV, luxury car, car rental UAE',
                        'slug' => 'bmw-x5',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'X5',
                        'meta_title' => 'بي ام دبليو X5',
                        'meta_description' => 'بي ام دبليو X5 هي سيارة دفع رباعي فاخرة تتميز بقدرة تحكم رياضية وأداء عالٍ.',
                        'meta_keywords' => 'بي ام دبليو X5, سيارة فاخرة, تأجير سيارات الإمارات',
                        'slug' => 'بي-ام-دبليو-x5',
                    ],
                ]
            ],
            // Add more models here as needed...
        ];

        foreach ($carModels as $model) {
            // Insert into car_models table
            $carModelId = DB::table('car_models')->insertGetId([
                'brand_id' => $model['brand_id'],
                'is_active' => $model['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($model['translations'] as $translation) {
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);
                // Insert translations into car_model_translations table
                DB::table('car_model_translations')->insert([
                    'car_model_id' => $carModelId,
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
