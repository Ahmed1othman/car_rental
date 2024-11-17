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
        Schema::create('static_translations', function (Blueprint $table) {
            $table->id();
            $table->string('key'); // Key for the translation
            $table->string('locale'); // Language code
            $table->text('value'); // Translation value
            $table->string('section')->nullable(); // Section to categorize translations
            $table->timestamps();

            // Create a composite unique index for key, locale, and section
            $table->unique(['key', 'locale', 'section'], 'unique_translation_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('static_translations');
    }
};
