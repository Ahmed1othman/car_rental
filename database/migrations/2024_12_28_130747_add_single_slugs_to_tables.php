<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Define tables that need migration
    protected $tables = [
        // 'cars',
        // 'blogs',
        // 'brands',
        // 'categories',
        // 'services',
        // 'features',
        // 'car_models',
        // 'templates',
        // 'locations',
        // 'body_styles',
        // 'colors',
        // 'gear_types',
        // 'makers',
        // 'documents',
        // 'short_videos'
        'homes',
        'abouts',
    ];

    protected $translationTables = [
        // 'car_translations',
        // 'blog_translations',
        // 'brand_translations',
        // 'category_translations',
        // 'service_translations',
        // 'feature_translations',
        // 'car_model_translations',
        // 'template_translations',
        // 'location_translations',
        // 'body_style_translations',
        // 'color_translations',
        // 'gear_type_translations',
        // 'maker_translations',
        // 'document_translations',
        // 'short_videos_translations'
        'home_translations',
        'about_translations',
    ];

    public function up()
    {
        // Add slug column to main tables
        foreach ($this->tables as $table) {
            if (!Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('slug')->unique()->nullable()->after('id');
                });
            }
        }
    }

    public function down()
    {
        // Remove slug column from main tables
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('slug');
                });
            }
        }

        // Add back slug column to translation tables
        foreach ($this->translationTables as $table) {
            if (!Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('slug')->unique()->nullable();
                });
            }
        }
    }
};
