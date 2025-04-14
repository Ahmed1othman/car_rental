<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateRobotsSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robots:update {--index=index : Set robots_index value (index/noindex)} {--follow=follow : Set robots_follow value (follow/nofollow)} {--tables= : Comma-separated list of specific tables to update (leave empty for all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update robots_index and robots_follow settings in all translation tables';

    /**
     * The translation tables that have robots columns.
     *
     * @var array
     */
    protected $translationTables = [
        'brand_translations',
        'car_translations',
        'category_translations',
        'home_translations',
        'location_translations',
        'blog_translations',
        'about_translations',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $robotsIndex = $this->option('index');
        $robotsFollow = $this->option('follow');
        $specificTables = $this->option('tables');

        // Validate robots_index value
        if (!in_array($robotsIndex, ['index', 'noindex'])) {
            $this->error('Invalid robots_index value. Must be "index" or "noindex".');
            return 1;
        }

        // Validate robots_follow value
        if (!in_array($robotsFollow, ['follow', 'nofollow'])) {
            $this->error('Invalid robots_follow value. Must be "follow" or "nofollow".');
            return 1;
        }

        // Get tables to update
        $tablesToUpdate = $this->translationTables;
        if (!empty($specificTables)) {
            $tablesToUpdate = explode(',', $specificTables);
        }

        $updatedTables = 0;
        $skippedTables = 0;

        foreach ($tablesToUpdate as $table) {
            // Check if table exists
            if (!Schema::hasTable($table)) {
                $this->warn("Table {$table} does not exist. Skipping.");
                $skippedTables++;
                continue;
            }

            // Check if table has robots columns
            if (!Schema::hasColumn($table, 'robots_index') || !Schema::hasColumn($table, 'robots_follow')) {
                $this->warn("Table {$table} does not have robots columns. Skipping.");
                $skippedTables++;
                continue;
            }

            // Update the table
            try {
                $count = DB::table($table)
                    ->update([
                        'robots_index' => $robotsIndex,
                        'robots_follow' => $robotsFollow
                    ]);

                $this->info("Updated {$count} rows in {$table}");
                $updatedTables++;
            } catch (\Exception $e) {
                $this->error("Error updating {$table}: " . $e->getMessage());
                $skippedTables++;
            }
        }

        $this->info("Command completed: {$updatedTables} tables updated, {$skippedTables} tables skipped.");
        return 0;
    }
}
