<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvertisementPositionSeeder extends Seeder
{
    public function run()
    {
        // Define the languages you want to seed
        $languages = [
            ['position_key' => 'home_first', 'position_name' => 'First Advertisement in Home' ],
            ['position_key' => 'home_second', 'position_name' => 'Second Advertisement in Home'],
            ['position_key' => 'home_third', 'position_name' => 'Third Advertisement in Home'],
            // Add more languages here if needed
        ];


        foreach ($languages as $language){
            DB::table('Advertisement_positions')->updateOrInsert(['position_key' => $language['position_key'] ], $language);
        }
    }
}
