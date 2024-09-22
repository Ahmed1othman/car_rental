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
        Schema::create('seo_question_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');  // Language code: 'en', 'ar', etc.
            $table->foreignId('seo_question_id')->references('id')->on('seo_questions')->cascadeOnDelete()->cascadeOnUpdate();
            // Translatable fields
            $table->text('question_text');  // The question text in various languages
            $table->text('answer_text')->nullable();  // The question text in various languages

            $table->timestamps();

            // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_question_translations', function (Blueprint $table) {
            $table->dropForeign(['seo_question_id']); // Drop the foreign key first
        });

        Schema::dropIfExists('seo_question_translations'); // Then drop the table
    }
};
