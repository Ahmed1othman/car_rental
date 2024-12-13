<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        // Truncate the tables to avoid duplicate entries
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('currencies')->truncate();
        DB::table('currency_translations')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // List of currencies
        $currencies = [
            [
                'code' => 'AED',
                'name' => 'AED',
                'symbol' => 'د.إ',
                'exchange_rate' => 1.0000,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'SAR',
                'name' => 'SAR',
                'symbol' => '﷼',
                'exchange_rate' => 1.0211,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'USD',
                'name' => 'USD',
                'symbol' => '$',
                'exchange_rate' => 0.2723,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'EUR',
                'name' => 'EUR',
                'symbol' => '€',
                'exchange_rate' => 0.2481,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'GBP',
                'name' => 'GBP',
                'symbol' => '£',
                'exchange_rate' => 0.2167,
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        // Active languages for translations
        $languages = [
            ['code' => 'en', 'name' => 'English', 'is_active' => true],
            ['code' => 'ar', 'name' => 'Arabic', 'is_active' => true],
            ['code' => 'fr', 'name' => 'French', 'is_active' => true],
            ['code' => 'de', 'name' => 'German', 'is_active' => true],
            ['code' => 'es', 'name' => 'Spanish', 'is_active' => true],
            ['code' => 'zh', 'name' => 'Chinese', 'is_active' => true],
            ['code' => 'it', 'name' => 'Italian', 'is_active' => true],
            ['code' => 'pt', 'name' => 'Portuguese', 'is_active' => true],
            ['code' => 'ru', 'name' => 'Russian', 'is_active' => true],
            ['code' => 'pl', 'name' => 'Polish', 'is_active' => true],
            ['code' => 'tr', 'name' => 'Turkish', 'is_active' => true],
        ];

        // Insert currencies into the `currencies` table
        foreach ($currencies as $currency) {
            $currencyId = DB::table('currencies')->insertGetId([
                'code' => $currency['code'],
                'symbol' => $currency['symbol'],
                'exchange_rate' => $currency['exchange_rate'],
                'is_default' => $currency['is_default'],
                'is_active' => $currency['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert translations for each active language into the `currencies_translations` table
            foreach ($languages as $language) {
                if ($language['is_active']) {
                    DB::table('currency_translations')->insert([
                        'name' => $this->translateCurrencyName($currency['name'], $language['code']),
                        'locale' => $language['code'],
                        'currency_id' => $currencyId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Translate the currency name based on the locale.
     * You can expand this function with a translation service or a predefined translation array.
     */
    private function translateCurrencyName($currencyName, $locale)
    {
        $translations = [
            'AED' => [
                'en' => 'AED',
                'ar' => 'درهم',
                'fr' => 'AED',
                'de' => 'AED',
            ],
            'SAR' => [
                'en' => 'SAR',
                'ar' => 'ريال',
                'fr' => 'SAR',
                'de' => 'SAR',
            ],
            'USD' => [
                'en' => 'USD',
                'ar' => 'دولار',
                'fr' => 'USD',
                'de' => 'USD',
            ],
            'EUR' => [
                'en' => 'EUR',
                'ar' => 'يورو',
                'fr' => 'EUR',
                'de' => 'EUR',
            ],
            'GBP' => [
                'en' => 'GBP',
                'ar' => 'جنيه استرليني',
                'fr' => 'GBP',
                'de' => 'GBP',
            ],
        ];


        // Return the translated name or fallback to English if no translation is available
        return $translations[$currencyName][$locale] ?? $currencyName;
    }
}
