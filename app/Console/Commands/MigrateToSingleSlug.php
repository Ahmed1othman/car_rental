<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class MigrateToSingleSlug extends Command
{
    protected $signature = 'migrate:to-single-slug';
    protected $description = 'Migrate from multi-language slugs to single English slugs';

    // Define tables that need migration
    protected $tables = [
        'cars' => 'car_translations',
        'blogs' => 'blog_translations',
        'brands' => 'brand_translations',
        'categories' => 'category_translations',
        'services' => 'service_translations',
        'features' => 'feature_translations',
        'car_models' => 'car_model_translations',
        'templates' => 'template_translations',
        'locations' => 'location_translations',
        'body_styles' => 'body_style_translations',
        'colors' => 'color_translations',
        'gear_types' => 'gear_type_translations',
        'makers' => 'maker_translations',
        'documents' => 'document_translations',
        'short_videos' => 'short_video_translations'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting migration to single slug system...');

        foreach ($this->tables as $mainTable => $translationTable) {
            $this->info("\nProcessing {$mainTable}...");

            // 1. Add slug column to main table if it doesn't exist
            if (!Schema::hasColumn($mainTable, 'slug')) {
                Schema::table($mainTable, function (Blueprint $table) {
                    $table->string('slug')->unique()->nullable()->after('id');
                });
                $this->info("Added slug column to {$mainTable}");
            }

            // 2. Copy English slugs from translation table
            $items = DB::table($translationTable)
                ->where('locale', 'en')
                ->whereNotNull('slug')
                ->get();

            $count = 0;
            foreach ($items as $item) {
                DB::table($mainTable)
                    ->where('id', $item->{$mainTable === 'categories' ? 'category_id' : rtrim($mainTable, 's') . '_id'})
                    ->update(['slug' => $item->slug]);
                $count++;
            }
            $this->info("Migrated {$count} slugs from {$translationTable} to {$mainTable}");

            // 3. Remove slug column from translation table
            if (Schema::hasColumn($translationTable, 'slug')) {
                Schema::table($translationTable, function (Blueprint $table) {
                    $table->dropColumn('slug');
                });
                $this->info("Removed slug column from {$translationTable}");
            }
        }

        $this->info("\nMigration completed successfully!");
    }
}
