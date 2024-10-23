<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the tables to clear existing data
        DB::table('category_translations')->truncate();
        DB::table('categories')->truncate();

        // Re-enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // List of car models with their translations
        $categories = [
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Sports Car',
                        'description' => 'High-performance cars for speed lovers.',
                        'meta_title' => 'Sports Car Rentals',
                        'meta_description' => 'Rent high-performance sports cars.',
                        'meta_keywords' => 'Sports Car, Car Rental',
                        'slug' => 'sports-car',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'سيارة رياضية',
                        'description' => 'سيارات عالية الأداء لعشاق السرعة.',
                        'meta_title' => 'تأجير سيارات رياضية',
                        'meta_description' => 'استئجار سيارات رياضية عالية الأداء.',
                        'meta_keywords' => 'سيارة رياضية, تأجير سيارات',
                        'slug' => 'سيارة-رياضية',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Convertible Car',
                        'description' => 'Open-top cars to enjoy the breeze.',
                        'meta_title' => 'Convertible Car Rentals',
                        'meta_description' => 'Enjoy the open air with convertible cars.',
                        'meta_keywords' => 'Convertible Car, Car Rental',
                        'slug' => 'convertible-car',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'سيارة مكشوفة',
                        'description' => 'سيارات مكشوفة للاستمتاع بالهواء النقي.',
                        'meta_title' => 'تأجير سيارات مكشوفة',
                        'meta_description' => 'استمتع بالهواء الطلق مع السيارات المكشوفة.',
                        'meta_keywords' => 'سيارة مكشوفة, تأجير سيارات',
                        'slug' => 'سيارة-مكشوفة',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Luxury Car',
                        'description' => 'Experience the ultimate in comfort and style.',
                        'meta_title' => 'Luxury Car Rentals',
                        'meta_description' => 'Rent luxurious cars for a premium experience.',
                        'meta_keywords' => 'Luxury Car, Car Rental',
                        'slug' => 'luxury-car',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'سيارة فاخرة',
                        'description' => 'تجربة الرفاهية والراحة بأسلوب فاخر.',
                        'meta_title' => 'تأجير سيارات فاخرة',
                        'meta_description' => 'استئجار سيارات فاخرة لتجربة متميزة.',
                        'meta_keywords' => 'سيارة فاخرة, تأجير سيارات',
                        'slug' => 'سيارة-فاخرة',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Supercar',
                        'description' => 'The fastest, most exotic cars available.',
                        'meta_title' => 'Supercar Rentals',
                        'meta_description' => 'Rent supercars for thrilling rides.',
                        'meta_keywords' => 'Supercar, Car Rental',
                        'slug' => 'supercar',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'سيارة خارقة',
                        'description' => 'أسرع السيارات الفاخرة المتاحة.',
                        'meta_title' => 'تأجير سيارات خارقة',
                        'meta_description' => 'استئجار سيارات خارقة لركوب مثير.',
                        'meta_keywords' => 'سيارة خارقة, تأجير سيارات',
                        'slug' => 'سيارة-خارقة',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'SUV',
                        'description' => 'Spacious and comfortable SUVs for family trips.',
                        'meta_title' => 'SUV Rentals',
                        'meta_description' => 'Rent SUVs for space and comfort.',
                        'meta_keywords' => 'SUV, Car Rental',
                        'slug' => 'suv',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'دفع رباعي',
                        'description' => 'سيارات دفع رباعي فسيحة ومريحة للعائلات.',
                        'meta_title' => 'تأجير سيارات دفع رباعي',
                        'meta_description' => 'استئجار سيارات دفع رباعي للراحة والمساحة.',
                        'meta_keywords' => 'دفع رباعي, تأجير سيارات',
                        'slug' => 'دفع-رباعي',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Hourly Car Rental',
                        'description' => 'Affordable short-term rentals by the hour.',
                        'meta_title' => 'Hourly Car Rentals',
                        'meta_description' => 'Rent cars on an hourly basis.',
                        'meta_keywords' => 'Hourly Car Rental, Car Rental',
                        'slug' => 'hourly-car-rental',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'تأجير بالساعة',
                        'description' => 'تأجير سيارات قصير المدة بالساعة.',
                        'meta_title' => 'تأجير سيارات بالساعة',
                        'meta_description' => 'استئجار سيارات على أساس الساعة.',
                        'meta_keywords' => 'تأجير بالساعة, تأجير سيارات',
                        'slug' => 'تأجير-بالساعة',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Daily Car Rental',
                        'description' => 'Convenient daily rental services.',
                        'meta_title' => 'Daily Car Rentals',
                        'meta_description' => 'Rent cars on a daily basis.',
                        'meta_keywords' => 'Daily Car Rental, Car Rental',
                        'slug' => 'daily-car-rental',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'تأجير يومي',
                        'description' => 'خدمات تأجير يومية مريحة.',
                        'meta_title' => 'تأجير سيارات يومي',
                        'meta_description' => 'استئجار سيارات على أساس يومي.',
                        'meta_keywords' => 'تأجير يومي, تأجير سيارات',
                        'slug' => 'تأجير-يومي',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Weekly Car Rental',
                        'description' => 'Affordable rentals for an entire week.',
                        'meta_title' => 'Weekly Car Rentals',
                        'meta_description' => 'Rent cars on a weekly basis.',
                        'meta_keywords' => 'Weekly Car Rental, Car Rental',
                        'slug' => 'weekly-car-rental',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'تأجير أسبوعي',
                        'description' => 'تأجير سيارات لمدة أسبوع بأسعار مناسبة.',
                        'meta_title' => 'تأجير سيارات أسبوعي',
                        'meta_description' => 'استئجار سيارات على أساس أسبوعي.',
                        'meta_keywords' => 'تأجير أسبوعي, تأجير سيارات',
                        'slug' => 'تأجير-أسبوعي',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Monthly Car Rental',
                        'description' => 'Long-term rentals on a monthly basis.',
                        'meta_title' => 'Monthly Car Rentals',
                        'meta_description' => 'Rent cars on a monthly basis.',
                        'meta_keywords' => 'Monthly Car Rental, Car Rental',
                        'slug' => 'monthly-car-rental',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'تأجير شهري',
                        'description' => 'تأجير طويل المدى على أساس شهري.',
                        'meta_title' => 'تأجير سيارات شهري',
                        'meta_description' => 'استئجار سيارات على أساس شهري.',
                        'meta_keywords' => 'تأجير شهري, تأجير سيارات',
                        'slug' => 'تأجير-شهري',
                    ],
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Exotic Car',
                        'description' => 'Unique and rare cars for a distinctive experience.',
                        'meta_title' => 'Exotic Car Rentals',
                        'meta_description' => 'Rent rare and exotic cars.',
                        'meta_keywords' => 'Exotic Car, Car Rental',
                        'slug' => 'exotic-car',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'سيارة غريبة',
                        'description' => 'سيارات نادرة وغريبة لتجربة مميزة.',
                        'meta_title' => 'تأجير سيارات غريبة',
                        'meta_description' => 'استئجار سيارات نادرة وغريبة.',
                        'meta_keywords' => 'سيارة غريبة, تأجير سيارات',
                        'slug' => 'سيارة-غريبة',
                    ],
                ],
            ],
        ];


        foreach ($categories as $model) {
            // Insert into Categories table
            $carModelId = DB::table('categories')->insertGetId([
                'is_active' => $model['is_active'],
                'image_path' => "images/default_category.png",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($model['translations'] as $translation) {
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);
                // Insert translations into Category_translations table
                DB::table('category_translations')->insert([
                    'category_id' => $carModelId,
                    'locale' => $translation['locale'],
                    'name' => $translation['name'],
                    'description' => $translation['description'],
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
