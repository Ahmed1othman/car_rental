<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class DropSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks and drop tables
        $this->command->info('Dropping tables...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('location_translations');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Tables dropped.');

        // Remove migrations from the migrations table
        $this->command->info('Removing migration entries...');
        DB::table('migrations')->whereIn('migration', [
            '2024_09_22_073920_create_locations_table',
            '2024_09_22_073921_create_location_translations_table',
        ])->delete();
        $this->command->info('Migration entries removed.');

        // Run migrations
        $this->command->info('Running migrations...');
        Artisan::call('migrate');
        $this->command->info('Migrations completed.');

        // Seed the tables
        $this->command->info('Seeding data...');
        Artisan::call('db:seed', ['--class' => 'LocationSeeder']);
        $this->command->info('Seeding completed.');
    }
}
