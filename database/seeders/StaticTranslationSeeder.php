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
        $locales = Language::whereNotIn('code', ['en','ar'])->get()->pluck('code')->toArray();

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
                'clear_filter' => 'clear filter',
                'close' => 'close',
                'price' => 'price',
                'read_more' =>'read more',
                'related_blogs' => 'related blogs',
                'message' => 'message',
                'send' => 'send',
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
                'transmission'=> 'transmission',
                'instagram_videos' => 'Instagram Videos',
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

                'whatsapp_text_one' => "Hi, I'm contacting you through ",
                'whatsapp_text_two' => "I'd like to inquire about",
            ],
        ];
        $translationsAr = [
            'menu' => [
                'home' => 'الصفحة الرئيسية',
                'brands' => 'العلامات التجارية',
                'categories' => 'فئات السيارات',
                'about_us' => 'من نحن',
                'contact_us' => 'اتصل بنا',
                'blog' => 'المدونة',
                'search' => 'ابحث عن سيارتك ...',
                'no_results' => 'لا توجد نتائج',
                'cars' => 'سيارات',
                'car' => 'سيارة',
            ],
            'card' => [
                'per_day' => 'في اليوم',
                'per_month' => 'في الشهر',
                'per_week' => 'في الأسبوع',
                'free_delivery' => 'توصيل مجاني',
                'insurance_included' => 'يشمل التأمين',
                'crypto_payment_accepted' => 'متاح الدفع بالعملات الرقمية',
                'km_per_day' => 'كم في اليوم',
                'km_per_month' => 'كم في الشهر',
                'km_per_week' => 'كم في الأسبوع',
                'km' => 'كم',
                'sale' => 'تخفيض',
                'no_deposit' => 'بدون تأمين',
                'brand' => 'العلامة التجارية',
                'model' => 'الموديل',
                'year' => 'السنة',
                'clear_filter' => 'إزالة التصفية',
                'close' => 'إغلاق',
                'price' => 'السعر',
                'read_more' => 'اقرأ المزيد',
                'related_blogs' => 'مقالات ذات صلة',
                'message' => 'الرسالة',
                'send' => 'إرسال',
                'category' => 'الفئة',
                'car_overview' => 'نظرة عامة على السيارة',
                'car_features' => 'ميزات السيارة',
                'related_cars' => 'سيارات ذات صلة',
                'car_description' => 'وصف السيارة',
            ],
            'footer' => [
                'brand_section' => 'العلامات التجارية',
                'quick_links' => 'روابط سريعة',
                'support' => 'الدعم',
                'available_payment_methods' => 'طرق الدفع المتاحة',
            ],
            'general' => [
                'view_all' => 'عرض الكل',
                'cars' => 'السيارات',
                'car' => 'سيارة',
                'no_results' => 'لا توجد نتائج',
                'transmission' => 'ناقل الحركة',
                'instagram_videos' => 'إنستجرام',
            ],
            'contact' => [
                'get_in_touch_with_us' => 'تواصل معنا',
                'submit' => 'إرسال',
                'social_media' => 'وسائل التواصل الاجتماعي',
                'full_name' => 'الاسم الكامل',
                'phone_number' => 'رقم الهاتف',
                'email' => 'البريد الإلكتروني',
                'subject' => 'الموضوع',
                'pricing' => 'التسعير',
                'call_us' => 'اتصل بنا',
                'whatsapp' => 'واتساب',
                'whatsapp_text_one' => "مرحبًا، أتواصل معكم عبر ",
                'whatsapp_text_two' => "أود الاستفسار عن ",
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

        foreach ($translationsAr as $section => $keys) {
            foreach ($keys as $key => $value) {
                // Create or update English translation
                $this->createOrUpdateTranslation('ar', $section, $key, $value);

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
