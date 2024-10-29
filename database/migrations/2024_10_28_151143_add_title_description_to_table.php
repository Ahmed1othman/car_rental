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
        Schema::table('home_translations', function (Blueprint $table) {
            $table->Text('blog_section_title')->nullable();
            $table->longText('blog_section_paragraph')->nullable();

            //contact_us
            $table->Text('contact_us_title')->nullable();
            $table->Text('contact_us_detail_title')->nullable();
            $table->longText('contact_us_paragraph')->nullable();
            $table->longText('contact_us_detail_paragraph')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_translations', function (Blueprint $table) {
            //
        });
    }
};
