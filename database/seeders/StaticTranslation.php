<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Stichoza\GoogleTranslate\GoogleTranslate;

class StaticTranslation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('static_translations')->truncate();

        $locales = Language::whereNot('code','en')->get()->pluck('code')->toArray();
        $translations = [
            'menu'=>[
                'home'=>'home page',
                'all_brands'=>'all brands',
                'categories'=>'car categories',
                'about_us'=>'about us',
                'contact_us'=>'contact us',
                'blog'=>'blog',
                'search'=>'find your car ...',
                'no_results'=>'no results',
                'cars'=>'Cars',
                'car'=>'car',
            ],
            'card'=>[
                'per_day'=>'per day',
                'per_month'=>'per month',
                'per_weak'=>'per weak',
                'free_delivery'=>'free delivery',
                'insurance_included'=>'insurance included',
                'crypto_payment_accepted'=>'crypto payment accepted',
                'km_per_day'=>'km per Day',
                'km_per_month'=>'Km per month',
                'km_per_week'=>'km per week',
                'km'=>'Km',
                'sale'=>'sale',
                'no_deposit'=>'no deposit',
                'brand'=>'brand',
                'model'=>'model',
                'year'=>'year',
                'colo'=>'colo',
                'category'=>'category',
                'car_over_view'=>'car over view',
                'car_features'=>'car features',
                'related_cars'=>'related cars',
                'car_description'=>'car description',
            ],
            'footer'=>[
                'brand_section'=>'brands',
                'quick_links'=>'quick links',
                'support'=>'support',
                'available_payment_methods'=>'available payment methods',
            ],
            'general'=>[
                'view_all'=>'view all',
                'cars'=>'cars',
                'car'=>'car',
                'no_results'=>'no results',
            ],

        ];

        foreach ($translations as $key_section => $section) {
            foreach ($section as $key => $value) {
                $enModel = \App\Models\StaticTranslation::create([
                    'key' => $key,
                    'locale' => 'en',
                    'value' => $value,
                    'section' => $key_section
                ]);

                foreach ($locales as $locale) {
                    $translatedName = $this->translateText($value ?? 'undefined', $locale);
                    \App\Models\StaticTranslation::create([
                        'key' => $key,
                        'locale' => $locale,
                        'value' => $translatedName,
                        'section' => $key_section
                    ]);
                }
            }
        }
    }

    private function translateText($text, $locale)
    {
        try {
            $translator = new GoogleTranslate($locale);
            return $translator->translate($text);
        } catch (\Exception $e) {
            return $text; // Fallback to the original text
        }
    }
}
