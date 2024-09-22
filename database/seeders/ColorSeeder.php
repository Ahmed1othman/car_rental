<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run()
    {
        // Full list of common car colors with Arabic and English translations
        $colors = [
            [
                'color_code' => '#000000',  // Black
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Black',
                        'meta_title' => 'Black Color',
                        'meta_description' => 'A sleek black color for cars.',
                        'meta_keywords' => 'black, car, color',
                        'slug' => 'black',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أسود',
                        'meta_title' => 'لون أسود',
                        'meta_description' => 'لون أسود أنيق للسيارات.',
                        'meta_keywords' => 'أسود, سيارة, لون',
                        'slug' => 'أسود',
                    ],
                ]
            ],
            [
                'color_code' => '#FFFFFF',  // White
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'White',
                        'meta_title' => 'White Color',
                        'meta_description' => 'A clean white color for cars.',
                        'meta_keywords' => 'white, car, color',
                        'slug' => 'white',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أبيض',
                        'meta_title' => 'لون أبيض',
                        'meta_description' => 'لون أبيض نظيف للسيارات.',
                        'meta_keywords' => 'أبيض, سيارة, لون',
                        'slug' => 'أبيض',
                    ],
                ]
            ],
            [
                'color_code' => '#FF0000',  // Red
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Red',
                        'meta_title' => 'Red Color',
                        'meta_description' => 'A bold red color for cars.',
                        'meta_keywords' => 'red, car, color',
                        'slug' => 'red',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أحمر',
                        'meta_title' => 'لون أحمر',
                        'meta_description' => 'لون أحمر جريء للسيارات.',
                        'meta_keywords' => 'أحمر, سيارة, لون',
                        'slug' => 'أحمر',
                    ],
                ]
            ],
            [
                'color_code' => '#0000FF',  // Blue
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Blue',
                        'meta_title' => 'Blue Color',
                        'meta_description' => 'A cool blue color for cars.',
                        'meta_keywords' => 'blue, car, color',
                        'slug' => 'blue',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أزرق',
                        'meta_title' => 'لون أزرق',
                        'meta_description' => 'لون أزرق هادئ للسيارات.',
                        'meta_keywords' => 'أزرق, سيارة, لون',
                        'slug' => 'أزرق',
                    ],
                ]
            ],
            [
                'color_code' => '#FFFF00',  // Yellow
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Yellow',
                        'meta_title' => 'Yellow Color',
                        'meta_description' => 'A bright yellow color for cars.',
                        'meta_keywords' => 'yellow, car, color',
                        'slug' => 'yellow',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أصفر',
                        'meta_title' => 'لون أصفر',
                        'meta_description' => 'لون أصفر مشرق للسيارات.',
                        'meta_keywords' => 'أصفر, سيارة, لون',
                        'slug' => 'أصفر',
                    ],
                ]
            ],
            [
                'color_code' => '#808080',  // Gray
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Gray',
                        'meta_title' => 'Gray Color',
                        'meta_description' => 'A sleek gray color for cars.',
                        'meta_keywords' => 'gray, car, color',
                        'slug' => 'gray',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'رمادي',
                        'meta_title' => 'لون رمادي',
                        'meta_description' => 'لون رمادي أنيق للسيارات.',
                        'meta_keywords' => 'رمادي, سيارة, لون',
                        'slug' => 'رمادي',
                    ],
                ]
            ],
            [
                'color_code' => '#800080',  // Purple
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Purple',
                        'meta_title' => 'Purple Color',
                        'meta_description' => 'A rich purple color for cars.',
                        'meta_keywords' => 'purple, car, color',
                        'slug' => 'purple',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أرجواني',
                        'meta_title' => 'لون أرجواني',
                        'meta_description' => 'لون أرجواني غني للسيارات.',
                        'meta_keywords' => 'أرجواني, سيارة, لون',
                        'slug' => 'أرجواني',
                    ],
                ]
            ],
            [
                'color_code' => '#008000',  // Green
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Green',
                        'meta_title' => 'Green Color',
                        'meta_description' => 'A vibrant green color for cars.',
                        'meta_keywords' => 'green, car, color',
                        'slug' => 'green',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'أخضر',
                        'meta_title' => 'لون أخضر',
                        'meta_description' => 'لون أخضر نابض بالحياة للسيارات.',
                        'meta_keywords' => 'أخضر, سيارة, لون',
                        'slug' => 'أخضر',
                    ],
                ]
            ],
            [
                'color_code' => '#FFA500',  // Orange
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Orange',
                        'meta_title' => 'Orange Color',
                        'meta_description' => 'A bold orange color for cars.',
                        'meta_keywords' => 'orange, car, color',
                        'slug' => 'orange',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'برتقالي',
                        'meta_title' => 'لون برتقالي',
                        'meta_description' => 'لون برتقالي جريء للسيارات.',
                        'meta_keywords' => 'برتقالي, سيارة, لون',
                        'slug' => 'برتقالي',
                    ],
                ]
            ],
            [
                'color_code' => '#A52A2A',  // Brown
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Brown',
                        'meta_title' => 'Brown Color',
                        'meta_description' => 'A rich brown color for cars.',
                        'meta_keywords' => 'brown, car, color',
                        'slug' => 'brown',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'بني',
                        'meta_title' => 'لون بني',
                        'meta_description' => 'لون بني غني للسيارات.',
                        'meta_keywords' => 'بني, سيارة, لون',
                        'slug' => 'بني',
                    ],
                ]
            ],
            [
                'color_code' => '#C0C0C0',  // Silver
                'is_active' => true,
                'translations' => [
                    [
                        'locale' => 'en',
                        'name' => 'Silver',
                        'meta_title' => 'Silver Color',
                        'meta_description' => 'A stylish silver color for cars.',
                        'meta_keywords' => 'silver, car, color',
                        'slug' => 'silver',
                    ],
                    [
                        'locale' => 'ar',
                        'name' => 'فضي',
                        'meta_title' => 'لون فضي',
                        'meta_description' => 'لون فضي أنيق للسيارات.',
                        'meta_keywords' => 'فضي, سيارة, لون',
                        'slug' => 'فضي',
                    ],
                ]
            ],
        ];

        foreach ($colors as $color) {
            // Try to get an existing color or create a new one
            $existingColor = DB::table('colors')
                ->where('color_code', $color['color_code'])
                ->first();

            if ($existingColor) {
                $colorId = $existingColor->id;
            } else {
                $colorId = DB::table('colors')->insertGetId([
                    'color_code' => $color['color_code'],
                    'is_active' => $color['is_active'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            foreach ($color['translations'] as $translation) {
                // Split keywords by commas and store as [{"value":"keyword1"},{"value":"keyword2"}]
                $metaKeywordsArray = explode(',', $translation['meta_keywords']);
                $metaKeywords = array_map(function ($keyword) {
                    return ['value' => trim($keyword)];
                }, $metaKeywordsArray);

                DB::table('color_translations')->updateOrInsert(
                    [
                        'color_id' => $colorId,
                        'locale' => $translation['locale'],
                    ],
                    [
                        'name' => $translation['name'],
                        'meta_title' => $translation['meta_title'],
                        'meta_description' => $translation['meta_description'],
                        'meta_keywords' => json_encode($metaKeywords), // Store in the correct format
                        'slug' => $translation['slug'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
            }
        }
    }
}
