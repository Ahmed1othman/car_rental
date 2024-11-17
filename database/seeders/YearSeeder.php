<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\AboutTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class YearSeeder extends Seeder
{
    public function run()
    {
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//
//        // Truncate the tables to clear existing data
//        DB::table('years')->truncate();
//
//        // Re-enable foreign key checks after truncating
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $startYear = 2015;
        $endYear = 2030;

        for ($year = $startYear; $year <= $endYear; $year++) {
            DB::table('years')->updateOrInsert(
                ['year' => $year], // Check if the year exists
                ['year' => $year]  // Insert or update with this value
            );
        }

    }
}
