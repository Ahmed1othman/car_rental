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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            // Basic contact information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('alternative_phone')->nullable();

            // Address information
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code');
            $table->string('country');

            // Social media links
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('snapchat')->nullable();

            // Other optional fields
            $table->string('website')->nullable();
            $table->string('google_map_url')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('additional_info')->nullable();

            // Status: Active or Disabled
            $table->boolean('is_active')->default(true);

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
