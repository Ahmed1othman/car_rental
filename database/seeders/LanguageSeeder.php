<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        // Define the languages you want to seed
        $languages = [
            ['code' => 'en', 'name' => 'English', 'is_active' => true],
            ['code' => 'ar', 'name' => 'Arabic', 'is_active' => true],
            ['code' => 'fr', 'name' => 'French', 'is_active' => false],
            ['code' => 'de', 'name' => 'German', 'is_active' => false],
            ['code' => 'es', 'name' => 'Spanish', 'is_active' => false],
            ['code' => 'zh', 'name' => 'Chinese', 'is_active' => false],
            ['code' => 'it', 'name' => 'Italian', 'is_active' => false],
            ['code' => 'pt', 'name' => 'Portuguese', 'is_active' => false],
            ['code' => 'ru', 'name' => 'Russian', 'is_active' => false],
            ['code' => 'pl', 'name' => 'Polish', 'is_active' => false],
            ['code' => 'tr', 'name' => 'Turkish', 'is_active' => false],
            // Add more languages here if needed
        ];


        foreach ($languages as $language){
            DB::table('languages')->updateOrInsert(['code' => $language['code'] ], $language);
        }
    }
}
