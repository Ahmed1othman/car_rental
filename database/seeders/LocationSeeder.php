<?php

namespace Database\Seeders;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Al Barsha'],
                    'ar' => ['name' => 'البرشاء']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Dubai Mall'],
                    'ar' => ['name' => 'دبي مول']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Business Bay'],
                    'ar' => ['name' => 'خليج الأعمال']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Jumeirah'],
                    'ar' => ['name' => 'جميرا']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Deira'],
                    'ar' => ['name' => 'ديرة']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Al Nahda'],
                    'ar' => ['name' => 'النهضة']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Downtown Dubai'],
                    'ar' => ['name' => 'داون تاون دبي']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Al Wasl'],
                    'ar' => ['name' => 'الوصل']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Yas Island'],
                    'ar' => ['name' => 'جزيرة ياس']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Khalifa City'],
                    'ar' => ['name' => 'مدينة خليفة']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Al Reem Island'],
                    'ar' => ['name' => 'جزيرة الريم']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Corniche'],
                    'ar' => ['name' => 'الكورنيش']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Al Falah'],
                    'ar' => ['name' => 'الفلاح']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Sharjah Corniche'],
                    'ar' => ['name' => 'كورنيش الشارقة']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Al Qasimia'],
                    'ar' => ['name' => 'القاسمية']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Muwaileh'],
                    'ar' => ['name' => 'مويلح']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Ajman Corniche'],
                    'ar' => ['name' => 'كورنيش عجمان']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Al Nuaimiya'],
                    'ar' => ['name' => 'النعيمية']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Umm Al Quwain Corniche'],
                    'ar' => ['name' => 'كورنيش أم القيوين']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Al Rams'],
                    'ar' => ['name' => 'الرمس']
                ],
            ],
            [
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'RAK Corniche'],
                    'ar' => ['name' => 'كورنيش رأس الخيمة']
                ],
            ],
            // Add more as needed...
        ];


        foreach ($locations as $location) {
            // Create the location in the database
            $newLocation = Location::create([
                'is_active' => $location['is_active'],
            ]);

            // Add translations for each language
            foreach ($location['translations'] as $locale => $translation) {
                $newLocation->translations()->create([
                    'locale' => $locale,
                    'name' => $translation['name'],
                ]);
            }
        }
    }
}
