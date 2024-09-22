<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateModule extends Command
{
    // Add the 'translation' or 't' option
    protected $signature = 'make:module {name} {--translation}';
    protected $description = 'Generate a new module with model, controller, views, and migration';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $hasTranslation = $this->option('translation'); // Check if translation option is passed

        // Generate the model with the content of Template model
        $this->generateModel($name);

        // Generate the controller and copy content from TemplateController
        $this->generateController($name);

        // Generate the migration for the main table
        Artisan::call('make:migration', ['name' => "create_{$this->getTableName($name)}_table"]);
        $this->updateMainMigration($name); // Modify the migration for the main table

        // Generate the translation model and migration if translation option is enabled
        if ($hasTranslation) {
            $this->generateTranslationModel($name);
            Artisan::call('make:migration', ['name' => "create_{$this->getTableName($name)}_translations_table"]);
            $this->createTranslationTable($name); // Create translation table migration
        }

        // Generate views using templates
        $this->generateViews($name);

        // Register routes
        $this->registerRoutes($name);

        // Update sidebar menu
        $this->updateSidebar($name);

        $this->info("Module {$name} generated successfully.");
    }

    protected function generateModel($name)
    {
        // Generate the model
        Artisan::call('make:model', ['name' => $name]);

        // Get the content of the Template model
        $templateModelPath = app_path('Models/Template.php');
        $generatedModelPath = app_path("Models/{$name}.php");

        if (File::exists($templateModelPath) && File::exists($generatedModelPath)) {
            $templateModelContent = File::get($templateModelPath);

            // Replace the class name in the content with the new model name
            $generatedModelContent = str_replace('Template', $name, $templateModelContent);

            // Save the modified content into the generated model
            File::put($generatedModelPath, $generatedModelContent);

            $this->info("Model {$name} generated and updated with Template model content.");
        }
    }

    protected function generateTranslationModel($name)
    {
        // Generate the translation model
        $translationModelName = "{$name}Translation";
        Artisan::call('make:model', ['name' => $translationModelName]);

        // Get the content of the TemplateTranslation model
        $templateTranslationModelPath = app_path('Models/TemplateTranslation.php');
        $generatedTranslationModelPath = app_path("Models/{$translationModelName}.php");

        if (File::exists($templateTranslationModelPath) && File::exists($generatedTranslationModelPath)) {
            $templateTranslationModelContent = File::get($templateTranslationModelPath);

            // Replace the class name in the content with the new translation model name
            $generatedTranslationModelContent = str_replace('TemplateTranslation', $translationModelName, $templateTranslationModelContent);

            // Also replace 'Template' with the singular version of the generated model
            $singularName = $this->getSingularTableName($name);
            $generatedTranslationModelContent = str_replace('Template', $singularName, $generatedTranslationModelContent);

            // Save the modified content into the generated translation model
            File::put($generatedTranslationModelPath, $generatedTranslationModelContent);

            $this->info("Translation model {$translationModelName} generated and updated with TemplateTranslation model content.");
        }
    }

    protected function getTableName($name)
    {
        return Str::plural(Str::snake($name));
    }

    protected function getSingularTableName($name)
    {
        return Str::singular(Str::snake($name));
    }

    protected function generateViews($name)
    {
        $viewsPath = resource_path("views/pages/admin/{$this->getTableName($name)}");
        File::makeDirectory($viewsPath, 0755, true);

        // Copy template views
        $templatePath = resource_path('views/pages/admin/templates');
        $views = ['index', 'create', 'edit', 'show'];

        foreach ($views as $view) {
            $templateFile = "{$templatePath}/{$view}.blade.php";
            $destinationFile = "{$viewsPath}/{$view}.blade.php";

            if (File::exists($templateFile)) {
                File::copy($templateFile, $destinationFile);
            } else {
                File::put($destinationFile, "<!-- {$name} {$view} view -->");
            }
        }

        $this->info("Views for {$name} created successfully.");
    }

    protected function registerRoutes($name)
    {
        $route = "Route::resource('" . Str::plural(Str::snake($name)) . "', 'App\Http\Controllers\admin\\{$name}Controller');";

        File::append(base_path('routes/admin.php'), "\n{$route}\n");

        $this->info("Routes for {$name} registered successfully.");
    }

    protected function updateMainMigration($name)
    {
        $tableName = $this->getTableName($name);
        $migrationFiles = File::files(database_path('migrations'));

        foreach ($migrationFiles as $migrationFile) {
            $migrationFileName = $migrationFile->getFilename();
            if (Str::contains($migrationFileName, "create_{$tableName}_table")) {
                $migrationPath = $migrationFile->getPathname();
                $migrationContent = File::get($migrationPath);

                // Define the columns for the main table
                $columns = [
                    "\$table->boolean('is_active')->default(true);",
                    // Other default columns for the main table can go here...
                ];

                // Add the columns before the closing Schema::create function
                $migrationContent = str_replace(
                    '});',
                    implode("\n", $columns) . "\n});",
                    $migrationContent
                );

                File::put($migrationPath, $migrationContent);
                $this->info("Main migration file {$migrationFileName} updated with additional columns.");
            }
        }
    }

    protected function generateController($name)
    {
        // Generate the controller
        Artisan::call('make:controller', ['name' => "admin/{$name}Controller", '--resource' => true]);

        // Copy content from TemplateController to the new controller
        $controllerPath = app_path("Http/Controllers/admin/{$name}Controller.php");
        $templateControllerPath = app_path('Http/Controllers/admin/TemplateController.php');
        if (File::exists($controllerPath)) {
            $templateControllerContent = File::get($templateControllerPath);
            File::put($controllerPath, $templateControllerContent);

            // Replace class name in the copied content
            $controllerContent = File::get($controllerPath);
            $controllerContent = str_replace('TemplateController', "{$name}Controller", $controllerContent);
            File::put($controllerPath, $controllerContent);

            // Update the controller to extend GenericController
            $controllerContent = File::get($controllerPath);
            $controllerContent = str_replace('extends Controller', 'extends GenericController', $controllerContent);
            File::put($controllerPath, $controllerContent);
        }

        $this->info("Controller for {$name} created successfully.");
    }

    protected function createTranslationTable($name)
    {
        $tableName = $this->getTableName($name.'_translation');
        $migrationFiles = File::files(database_path('migrations'));

        foreach ($migrationFiles as $migrationFile) {
            $migrationFileName = $migrationFile->getFilename();
            if (Str::contains($migrationFileName, "create_'.$tableName.'_table")) {
                $migrationPath = $migrationFile->getPathname();
                $migrationContent = File::get($migrationPath);

                // Translation table structure
                $columns = [
                    "\$table->id();",
                    "\$table->string('locale');  // e.g., 'en', 'ar'",
                    "\$table->text('meta_title')->nullable();",
                    "\$table->text('meta_description')->nullable();",
                    "\$table->text('meta_keywords')->nullable();",
                    "\$table->string('slug')->unique()->nullable();",
                    "\$table->timestamps();",
                    "\$table->foreignId('{$this->getSingularTableName($name)}_id')->constrained('{$tableName}')->cascadeOnUpdate()->cascadeOnDelete();"
                ];

                // Replace the migration content with the translation columns
                $migrationContent = str_replace(
                    '});',
                    implode("\n", $columns) . "\n});",
                    $migrationContent
                );

                File::put($migrationPath, $migrationContent);
                $this->info("Translation migration file {$migrationFileName} updated successfully.");
            }
        }
    }

    protected function updateSidebar($name)
    {
        $sidebarPath = base_path('config/sidebar.php');
        $sidebarConfig = File::exists($sidebarPath) ? require $sidebarPath : ['menu' => []];

        $newMenuItem = [
            'title' => ucfirst($name),
            'icon' => 'fas fa-box', // Default icon, you can customize this based on the module
            'subMenu' => [
                ['title' => 'Add ' . ucfirst($name), 'route' => "admin.{$this->getTableName($name)}.create"],
                ['title' => 'List ' . ucfirst($name), 'route' => "admin.{$this->getTableName($name)}.index"],
            ],
        ];

        // Append the new menu item
        $sidebarConfig['menu'][] = $newMenuItem;

        // Write updated sidebar config to file
        File::put($sidebarPath, '<?php return ' . var_export($sidebarConfig, true) . ';');
        $this->info("Sidebar menu updated with {$name} module.");
    }

}
