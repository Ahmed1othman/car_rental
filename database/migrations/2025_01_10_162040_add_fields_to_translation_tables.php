<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add fields to brand translations
        Schema::table('brand_translations', function (Blueprint $table) {
            $table->string('title')->nullable()->after('brand_id');
            $table->text('description')->nullable()->after('title');
            $table->longText('article')->nullable()->after('description');
        });

        // Add fields to category translations
        Schema::table('category_translations', function (Blueprint $table) {
            $table->string('title')->nullable()->after('category_id');
            $table->longText('article')->nullable()->after('title');
        });

        // Add fields to location translations
        Schema::table('location_translations', function (Blueprint $table) {
            $table->string('title')->nullable()->after('location_id');
            $table->longText('article')->nullable()->after('title');
            $table->dropColumn('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brand_translations', function (Blueprint $table) {
            $table->dropColumn(['title', 'article']);
        });

        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropColumn(['title', 'article']);
        });

        Schema::table('location_translations', function (Blueprint $table) {
            $table->dropColumn(['title', 'article']);
            $table->text('content')->nullable();
        });
    }
};
