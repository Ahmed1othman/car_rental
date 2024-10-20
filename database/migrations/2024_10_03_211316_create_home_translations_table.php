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
        Schema::create('home_translations', function (Blueprint $table) {
            $table->id();
            $table->text('hero_header_title')->nullable();

            $table->longText('car_only_section_title')->nullable();
            $table->longText('car_only_section_paragraph')->nullable();

            $table->longText('special_offers_section_title')->nullable();
            $table->longText('special_offers_section_paragraph')->nullable();

            $table->longText('why_choose_us_section_title')->nullable();
            $table->longText('why_choose_us_section_paragraph')->nullable();

            $table->longText('featured_cars_section_title')->nullable();
            $table->longText('featured_cars_section_paragraph')->nullable();

            $table->longText('faq_section_title')->nullable();
            $table->longText('faq_section_paragraph')->nullable();

            $table->longText('where_find_us_section_title')->nullable();
            $table->longText('where_find_us_section_paragraph')->nullable();

            $table->longText('required_documents_section_title')->nullable();
            $table->longText('required_documents_section_paragraph')->nullable();

            $table->longText('instagram_section_title')->nullable();

            $table->longText('footer_section_paragraph')->nullable();

            $table->string('locale');  // e.g., 'en', 'ar'
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
            $table->foreignId('home_id')->constrained('homes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_translations');
    }
};
