<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\StaticTranslation;
use Illuminate\Database\Seeder;
use Stichoza\GoogleTranslate\GoogleTranslate;

class StaticTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = Language::whereNot('code', 'en')->get()->pluck('code')->toArray();

        $translations = [
            'menu' => [
                'home' => 'home page',
                'all_brands' => 'all brands',
                'categories' => 'car categories',
                'about_us' => 'about us',
                'contact_us' => 'contact us',
                'blog' => 'blog',
                'search' => 'find your car ...',
                'no_results' => 'no results',
                'cars' => 'Cars',
                'car' => 'car',
            ],
            'card' => [
                'per_day' => 'per day',
                'per_month' => 'per month',
                'per_weak' => 'per weak',
                'free_delivery' => 'free delivery',
                'insurance_included' => 'insurance included',
                'crypto_payment_accepted' => 'crypto payment accepted',
                'km_per_day' => 'km per Day',
                'km_per_month' => 'Km per month',
                'km_per_week' => 'km per week',
                'km' => 'Km',
                'sale' => 'sale',
                'no_deposit' => 'no deposit',
                'brand' => 'brand',
                'model' => 'model',
                'year' => 'year',
                'colo' => 'colo',
                'category' => 'category',
                'car_over_view' => 'car over view',
                'car_features' => 'car features',
                'related_cars' => 'related cars',
                'car_description' => 'car description',
            ],
            'footer' => [
                'brand_section' => 'brands',
                'quick_links' => 'quick links',
                'support' => 'support',
                'available_payment_methods' => 'available payment methods',
            ],
            'general' => [
                'view_all' => 'view all',
                'cars' => 'cars',
                'car' => 'car',
                'no_results' => 'no results',
            ],
            'contact' => [
                'get_in_touch_with_us' => 'get in touch with us',
                'submit' => 'submit',
                'social_media' => 'social media',
                'full_name' => 'full name',
                'phone_number' => 'phone number',
                'email' => 'email',
                'subject' => 'subject',
                'pricing' => 'pricing',
                'call_us' => 'call us',
                'whatsapp' => 'whatsApp',
            ],
        ];

        foreach ($translations as $section => $keys) {
            foreach ($keys as $key => $value) {
                // English translation (base language)
                StaticTranslation::updateOrCreate(
                    [
                        'key' => $key,
                        'locale' => 'en',
                    ],
                    [
                        'value' => $value,
                        'section' => $section,
                    ]
                );

                // Translations for other locales
                foreach ($locales as $locale) {
                    $translatedValue = $this->translateText($value, $locale);
                    StaticTranslation::updateOrCreate(
                        [
                            'key' => $key,
                            'locale' => $locale,
                        ],
                        [
                            'value' => $translatedValue,
                            'section' => $section,
                        ]
                    );
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
