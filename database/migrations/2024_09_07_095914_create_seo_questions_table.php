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
        Schema::create('seo_questions', function (Blueprint $table) {
            $table->id();
            $table->morphs('seo_questionable');  // Polymorphic relationship: 'seo_questionable_id' and 'seo_questionable_type'
            $table->string('locale');  // Language code: 'en', 'ar', etc.
            // Translatable fields
            $table->text('question_text');  // The question text in various languages
            $table->text('answer_text')->nullable();  // The question text in various languages
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_questions');
    }
};
