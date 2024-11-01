<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Gear_type;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            CurrencySeeder::class,
            ColorSeeder::class,
            ContactSeeder::class,
            BrandSeeder::class,
            GearTypeSeeder::class,
            GearTypeSeeder::class,
            AboutPageSeeder::class,
            HomePageSeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
            UserSeeder::class,
            AdvertisementPositionSeeder::class,
            FeatureSeeder::class,
            IconSeeder::class,
        ]);
    }
}
