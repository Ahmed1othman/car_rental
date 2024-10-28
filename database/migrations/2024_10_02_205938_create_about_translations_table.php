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
        Schema::create('about_translations', function (Blueprint $table) {
            $table->id();
            $table->text('about_main_header_title')->nullable();
            $table->longText('about_main_header_paragraph')->nullable();
            $table->text('about_our_agency_title')->nullable();
            $table->text('why_choose_title')->nullable();
            $table->text('our_vision_title')->nullable();
            $table->text('our_mission_title')->nullable();
            $table->longText('why_choose_content')->nullable();
            $table->longText('our_vision_content')->nullable();
            $table->longText('our_mission_content')->nullable();
            $table->string('locale');  // e.g., 'en', 'ar'
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
            $table->foreignId('about_id')->constrained('abouts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_translations');
    }
};
