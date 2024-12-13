<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the tables to clear existing data
        DB::table('brand_translations')->truncate();
        DB::table('brands')->truncate();

        // Re-enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // List of 15 popular car brands for car rentals in Dubai
        $brands = [
            [
                'logo_path' => 'images/toyota.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Toyota',
                        'meta_title' => 'Toyota Car Rentals in Dubai',
                        'meta_description' => 'Rent reliable and popular Toyota cars in Dubai.',
                        'meta_keywords' => 'Toyota, Car Rental, Dubai',
                        'slug' => 'toyota',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'تويوتا',
                        'meta_title' => 'تأجير سيارات تويوتا في دبي',
                        'meta_description' => 'استأجر سيارات تويوتا الموثوقة والشائعة في دبي.',
                        'meta_keywords' => 'تويوتا, تأجير سيارات, دبي',
                        'slug' => 'تويوتا',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/nissan.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Nissan',
                        'meta_title' => 'Nissan Car Rentals in Dubai',
                        'meta_description' => 'Explore Dubai with high-performance Nissan cars.',
                        'meta_keywords' => 'Nissan, Car Rental, Dubai',
                        'slug' => 'nissan',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'نيسان',
                        'meta_title' => 'تأجير سيارات نيسان في دبي',
                        'meta_description' => 'استكشاف دبي مع سيارات نيسان عالية الأداء.',
                        'meta_keywords' => 'نيسان, تأجير سيارات, دبي',
                        'slug' => 'نيسان',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/mercedes.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Mercedes-Benz',
                        'meta_title' => 'Mercedes Car Rentals in Dubai',
                        'meta_description' => 'Rent luxurious Mercedes-Benz cars for a premium experience.',
                        'meta_keywords' => 'Mercedes-Benz, Luxury Car, Dubai',
                        'slug' => 'mercedes-benz',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'مرسيدس بنز',
                        'meta_title' => 'تأجير سيارات مرسيدس بنز في دبي',
                        'meta_description' => 'استأجر سيارات مرسيدس الفاخرة لتجربة متميزة.',
                        'meta_keywords' => 'مرسيدس, سيارات فاخرة, دبي',
                        'slug' => 'مرسيدس-بنز',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/bmw.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'BMW',
                        'meta_title' => 'BMW Car Rentals in Dubai',
                        'meta_description' => 'Drive elegant and powerful BMW cars in Dubai.',
                        'meta_keywords' => 'BMW, Car Rental, Dubai',
                        'slug' => 'bmw',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'بي إم دبليو',
                        'meta_title' => 'تأجير سيارات بي إم دبليو في دبي',
                        'meta_description' => 'قد سيارات بي إم دبليو الأنيقة والقوية في دبي.',
                        'meta_keywords' => 'بي إم دبليو, تأجير سيارات, دبي',
                        'slug' => 'بي-ام-دبليو',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/audi.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Audi',
                        'meta_title' => 'Audi Car Rentals in Dubai',
                        'meta_description' => 'Rent stylish Audi cars for your trips around Dubai.',
                        'meta_keywords' => 'Audi, Car Rental, Dubai',
                        'slug' => 'audi',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أودي',
                        'meta_title' => 'تأجير سيارات أودي في دبي',
                        'meta_description' => 'استأجر سيارات أودي الأنيقة لرحلاتك في دبي.',
                        'meta_keywords' => 'أودي, تأجير سيارات, دبي',
                        'slug' => 'أودي',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/chevrolet.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Chevrolet',
                        'meta_title' => 'Chevrolet Car Rentals in Dubai',
                        'meta_description' => 'Rent Chevrolet cars for a smooth and powerful driving experience.',
                        'meta_keywords' => 'Chevrolet, Car Rental, Dubai',
                        'slug' => 'chevrolet',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'شيفروليه',
                        'meta_title' => 'تأجير سيارات شيفروليه في دبي',
                        'meta_description' => 'استأجر سيارات شيفروليه لتجربة قيادة سلسة وقوية.',
                        'meta_keywords' => 'شيفروليه, تأجير سيارات, دبي',
                        'slug' => 'شيفروليه',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/ford.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Ford',
                        'meta_title' => 'Ford Car Rentals in Dubai',
                        'meta_description' => 'Experience the power and reliability of Ford cars in Dubai.',
                        'meta_keywords' => 'Ford, Car Rental, Dubai',
                        'slug' => 'ford',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'فورد',
                        'meta_title' => 'تأجير سيارات فورد في دبي',
                        'meta_description' => 'استمتع بقوة وموثوقية سيارات فورد في دبي.',
                        'meta_keywords' => 'فورد, تأجير سيارات, دبي',
                        'slug' => 'فورد',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/lexus.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Lexus',
                        'meta_title' => 'Lexus Car Rentals in Dubai',
                        'meta_description' => 'Drive elegant and luxurious Lexus cars for an unforgettable experience.',
                        'meta_keywords' => 'Lexus, Car Rental, Dubai',
                        'slug' => 'lexus',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'لكزس',
                        'meta_title' => 'تأجير سيارات لكزس في دبي',
                        'meta_description' => 'قد سيارات لكزس الأنيقة والفاخرة لتجربة لا تُنسى.',
                        'meta_keywords' => 'لكزس, تأجير سيارات, دبي',
                        'slug' => 'لكزس',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/rolls_royce.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Rolls-Royce',
                        'meta_title' => 'Rolls-Royce Rentals in Dubai',
                        'meta_description' => 'Rent luxurious Rolls-Royce cars for a premium experience in Dubai.',
                        'meta_keywords' => 'Rolls-Royce, Luxury Car, Car Rental, Dubai',
                        'slug' => 'rolls-royce',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'رولز رويس',
                        'meta_title' => 'تأجير سيارات رولز رويس في دبي',
                        'meta_description' => 'استأجر سيارات رولز رويس الفاخرة لتجربة فاخرة في دبي.',
                        'meta_keywords' => 'رولز رويس, سيارة فاخرة, تأجير سيارات, دبي',
                        'slug' => 'رولز-رويس',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/ferrari.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Ferrari',
                        'meta_title' => 'Ferrari Car Rentals in Dubai',
                        'meta_description' => 'Experience the thrill of driving a Ferrari in Dubai.',
                        'meta_keywords' => 'Ferrari, Car Rental, Sports Car, Dubai',
                        'slug' => 'ferrari',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'فيراري',
                        'meta_title' => 'تأجير سيارات فيراري في دبي',
                        'meta_description' => 'استمتع بقيادة سيارات فيراري السريعة في دبي.',
                        'meta_keywords' => 'فيراري, تأجير سيارات, سيارة رياضية, دبي',
                        'slug' => 'فيراري',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/lamborghini.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Lamborghini',
                        'meta_title' => 'Lamborghini Car Rentals in Dubai',
                        'meta_description' => 'Drive exotic Lamborghini cars for an unforgettable experience.',
                        'meta_keywords' => 'Lamborghini, Car Rental, Exotic Car, Dubai',
                        'slug' => 'lamborghini',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'لامبورغيني',
                        'meta_title' => 'تأجير سيارات لامبورغيني في دبي',
                        'meta_description' => 'قد سيارات لامبورغيني الغريبة لتجربة لا تُنسى.',
                        'meta_keywords' => 'لامبورغيني, تأجير سيارات, سيارة غريبة, دبي',
                        'slug' => 'لامبورغيني',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/porsche.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Porsche',
                        'meta_title' => 'Porsche Car Rentals in Dubai',
                        'meta_description' => 'Drive stylish Porsche cars for an unforgettable experience.',
                        'meta_keywords' => 'Porsche, Car Rental, Luxury Car, Dubai',
                        'slug' => 'porsche',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'بورش',
                        'meta_title' => 'تأجير سيارات بورش في دبي',
                        'meta_description' => 'قد سيارات بورش الأنيقة لتجربة فريدة من نوعها.',
                        'meta_keywords' => 'بورش, تأجير سيارات, سيارة فاخرة, دبي',
                        'slug' => 'بورش',
                    ],
                ],
            ],

            [
                'logo_path' => 'images/range_rover.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Range Rover',
                        'meta_title' => 'Range Rover Rentals in Dubai',
                        'meta_description' => 'Experience the comfort and power of Range Rover cars.',
                        'meta_keywords' => 'Range Rover, Luxury Car, Car Rental, Dubai',
                        'slug' => 'range-rover',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'رينج روفر',
                        'meta_title' => 'تأجير سيارات رينج روفر في دبي',
                        'meta_description' => 'استمتع بالراحة والقوة مع سيارات رينج روفر.',
                        'meta_keywords' => 'رينج روفر, سيارة فاخرة, تأجير سيارات, دبي',
                        'slug' => 'رينج-روفر',
                    ],
                ],
            ],
            // Add more brands in a similar format to reach 15 brands
            [
                'logo_path' => 'images/kia.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Kia',
                        'meta_title' => 'Kia Car Rentals in Dubai',
                        'meta_description' => 'Rent reliable and fuel-efficient Kia cars in Dubai.',
                        'meta_keywords' => 'Kia, Car Rental, Dubai',
                        'slug' => 'Kia',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'كيا',
                        'meta_title' => 'تأجير سيارات كيا في دبي',
                        'meta_description' => 'استأجر سيارات كيا الموثوقة والموفرة للوقود في دبي.',
                        'meta_keywords' => 'كيا, تأجير سيارات, دبي',
                        'slug' => 'كيا',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/hyundai.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Hyundai',
                        'meta_title' => 'Hyundai Car Rentals in Dubai',
                        'meta_description' => 'Rent reliable and fuel-efficient Hyundai cars in Dubai.',
                        'meta_keywords' => 'Hyundai, Car Rental, Dubai',
                        'slug' => 'Hyundai',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'هيونداي',
                        'meta_title' => 'تأجير سيارات هوندا في دبي',
                        'meta_description' => 'استأجر سيارات هيونداي الموثوقة والموفرة للوقود في دبي.',
                        'meta_keywords' => 'هيونداي, تأجير سيارات, دبي',
                        'slug' => 'هيونداي',
                    ],
                ],
            ],
            [
                'logo_path' => 'images/honda.webp',
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Honda',
                        'meta_title' => 'Honda Car Rentals in Dubai',
                        'meta_description' => 'Rent reliable and fuel-efficient Honda cars in Dubai.',
                        'meta_keywords' => 'Honda, Car Rental, Dubai',
                        'slug' => 'honda',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'هوندا',
                        'meta_title' => 'تأجير سيارات هوندا في دبي',
                        'meta_description' => 'استأجر سيارات هوندا الموثوقة والموفرة للوقود في دبي.',
                        'meta_keywords' => 'هوندا, تأجير سيارات, دبي',
                        'slug' => 'هوندا',
                    ],
                ],
            ],


            // Additional brands: Kia, Hyundai, Land Rover, and so on...
        ];

        foreach ($brands as $brand) {
            $existingBrand = DB::table('brands')->where('logo_path', $brand['logo_path'])->first();

            if ($existingBrand) {
                $brandId = $existingBrand->id;
            } else {
                $brandId = DB::table('brands')->insertGetId([
                    'is_active' => $brand['is_active'],
                    'logo_path' => $brand['logo_path'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            foreach ($brand['translations'] as $translation) {
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);
                DB::table('brand_translations')->insert([
                    'brand_id' => $brandId,
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
