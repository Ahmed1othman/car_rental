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
        Schema::table('homes', function (Blueprint $table) {
            $table->text('hero_header_image_path')->nullable()->after('hero_header_video_path');
            $table->enum('hero_type',['video','image'])->default('image')->after('hero_header_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn('hero_header_image_path');
            $table->dropColumn('hero_type');
        });
    }
};
