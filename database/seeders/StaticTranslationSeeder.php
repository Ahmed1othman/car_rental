<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\StaticTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class StaticTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // Fetch all active locales except English
        $locales = Language::whereNot('code', 'en')->get()->pluck('code')->toArray();

        // Static translations structure
        $translations = [
            'menu' => [
                'home' => 'home page',
                'brands' => 'brands',
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
                'per_week' => 'per week',
                'free_delivery' => 'free delivery',
                'insurance_included' => 'insurance included',
                'crypto_payment_accepted' => 'crypto payment accepted',
                'km_per_day' => 'km per day',
                'km_per_month' => 'km per month',
                'km_per_week' => 'km per week',
                'km' => 'Km',
                'sale' => 'sale',
                'no_deposit' => 'no deposit',
                'brand' => 'brand',
                'model' => 'model',
                'year' => 'year',
                'color' => 'color',
                'category' => 'category',
                'car_overview' => 'car overview',
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

        // Insert translations
        foreach ($translations as $section => $keys) {
            foreach ($keys as $key => $value) {
                // Create or update English translation
                $this->createOrUpdateTranslation('en', $section, $key, $value);

                // Create or update translations for other locales
                foreach ($locales as $locale) {
                    $translatedValue = $this->translateText($value, $locale);
                    $this->createOrUpdateTranslation($locale, $section, $key, $translatedValue);
                }
            }
        }
    }

    /**
     * Create or update a translation entry in the database.
     */
    private function createOrUpdateTranslation(string $locale, string $section, string $key, string $value): void
    {
        $translation = StaticTranslation::firstOrNew([
            'key' => $key,
            'locale' => $locale,
            'section' => $section,
        ]);

        $translation->value = $value;
        $translation->save();
    }

    /**
     * Translate text to the given locale using GoogleTranslate.
     */
    private function translateText(string $text, string $locale): string
    {
        try {
            $translator = new GoogleTranslate($locale);
            return $translator->translate($text);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error("Translation error: " . $e->getMessage());
            return $text; // Return the original text as fallback
        }
    }
}
