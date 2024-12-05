<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $translationTables = [
            'brand_translations',
            'car_translations',
            'category_translations',
            'home_translations',
            'location_translations',
            'blog_translations',
            'about_translations',
        ];

        foreach ($translationTables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->enum('robots_index', ['index', 'noindex'])->default('index')->after('meta_keywords')->nullable();
                $table->enum('robots_follow', ['follow', 'nofollow'])->default('follow')->after('robots_index')->nullable();
            });
        }
    }

    public function down()
    {
        $translationTables = [
            'brand_translations',
            'car_translations',
            'category_translations',
            'home_translations',
            'location_translations',
            'blog_translations',
            'about_translations',
        ];

        foreach ($translationTables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['robots_index', 'robots_follow']);
            });
        }
    }
};

