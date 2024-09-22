<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RollbackModule extends Command
{
    // Add the 'translation' option to rollback translation-related files as well
    protected $signature = 'rollback:module {name} {--translation}';
    protected $description = 'Rollback a previously generated module with models, migrations, views, and routes';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $hasTranslation = $this->option('translation'); // Check if translation option is passed

        // Rollback the generated models
        $this->rollbackModel($name);
        if ($hasTranslation) {
            $this->rollbackTranslationModel($name);
        }

        // Rollback the migrations
        $this->rollbackMigrations($name, $hasTranslation);

        // Rollback the views
        $this->rollbackViews($name);

        // Rollback the routes
        $this->rollbackRoutes($name);

        // Rollback the sidebar menu
        $this->rollbackSidebar($name);

        $this->info("Module {$name} rollback completed successfully.");
    }

    protected function rollbackModel($name)
    {
        // Delete the generated model
        $modelPath = app_path("Models/{$name}.php");
        if (File::exists($modelPath)) {
            File::delete($modelPath);
            $this->info("Model {$name} deleted successfully.");
        }
    }

    protected function rollbackTranslationModel($name)
    {
        // Delete the generated translation model
        $translationModelName = "{$name}Translation";
        $translationModelPath = app_path("Models/{$translationModelName}.php");
        if (File::exists($translationModelPath)) {
            File::delete($translationModelPath);
            $this->info("Translation model {$translationModelName} deleted successfully.");
        }
    }

    protected function rollbackMigrations($name, $hasTranslation)
    {
        // Rollback the main table migration
        $tableName = $this->getTableName($name);
        $migrationFiles = File::files(database_path('migrations'));

        foreach ($migrationFiles as $migrationFile) {
            $migrationFileName = $migrationFile->getFilename();
            if (Str::contains($migrationFileName, "create_{$tableName}_table")) {
                File::delete($migrationFile->getPathname());
                $this->info("Main migration {$migrationFileName} deleted successfully.");
            }

            if ($hasTranslation && Str::contains($migrationFileName, "create_{$tableName}_translations_table")) {
                File::delete($migrationFile->getPathname());
                $this->info("Translation migration {$migrationFileName} deleted successfully.");
            }
        }
    }

    protected function rollbackViews($name)
    {
        // Delete the generated views
        $viewsPath = resource_path("views/pages/admin/{$this->getTableName($name)}");
        if (File::exists($viewsPath)) {
            File::deleteDirectory($viewsPath);
            $this->info("Views for {$name} deleted successfully.");
        }
    }

    protected function rollbackRoutes($name)
    {
        $routeFile = base_path('routes/admin.php');
        if (File::exists($routeFile)) {
            $routeContent = File::get($routeFile);
            $routeDefinition = "Route::resource('" . Str::plural(Str::snake($name)) . "', 'App\Http\Controllers\admin\\{$name}Controller');";
            if (Str::contains($routeContent, $routeDefinition)) {
                $newRouteContent = str_replace($routeDefinition, '', $routeContent);
                File::put($routeFile, $newRouteContent);
                $this->info("Routes for {$name} removed successfully.");
            }
        }
    }

    protected function rollbackSidebar($name)
    {
        $sidebarPath = base_path('config/sidebar.php');
        if (File::exists($sidebarPath)) {
            $sidebarConfig = require $sidebarPath;

            // Find and remove the sidebar menu item
            $sidebarConfig['menu'] = array_filter($sidebarConfig['menu'], function ($item) use ($name) {
                return strtolower($item['title']) !== strtolower($name);
            });

            // Write the updated sidebar config back to file
            File::put($sidebarPath, '<?php return ' . var_export($sidebarConfig, true) . ';');
            $this->info("Sidebar menu for {$name} removed successfully.");
        }
    }

    protected function getTableName($name): string
    {
        return Str::plural(Str::snake($name));
    }
}
