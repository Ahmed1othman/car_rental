<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{

    public function run()
    {
        DB::table('currencies')->truncate();

        $currencies = [
            [
                'code' => 'AED',
                'name' => 'Emirati Dirham',
                'symbol' => 'د.إ',
                'exchange_rate' => 1.0000,
                'is_default' => true,
            ],
            [
                'code' => 'SAR',
                'name' => 'Saudi Riyal',
                'symbol' => '﷼',
                'exchange_rate' => 1.0211,
                'is_default' => false,
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 0.2723,
                'is_default' => false,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 0.2481,
                'is_default' => false,
            ],
        ];

        foreach ($currencies as $currency) {
            DB::table('currencies')->insert($currency + ['is_active'=>true]);
        }
    }
    }
