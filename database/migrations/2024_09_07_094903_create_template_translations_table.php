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
        Schema::create('template_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');  // e.g., 'en', 'ar'

            // Translatable fields
            $table->string('name');  // Template name (multilingual)
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('slug')->unique()->nullable();

            $table->timestamps();

            // Foreign key constraint
            $table->foreignId('template_id')->references('id')->on('templates')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_translations', function (Blueprint $table) {
            $table->dropForeign(['template_id']); // Drop the foreign key first
        });

        Schema::dropIfExists('template_translations');
    }
};
