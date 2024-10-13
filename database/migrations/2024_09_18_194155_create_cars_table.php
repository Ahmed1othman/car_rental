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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->decimal('daily_main_price', 10, 2);
            $table->decimal('daily_discount_price', 10, 2)->nullable();
            $table->decimal('weekly_main_price', 10, 2)->nullable();
            $table->decimal('weekly_discount_price', 10, 2)->nullable();
            $table->decimal('monthly_main_price', 10, 2);
            $table->decimal('monthly_discount_price', 10, 2)->nullable();
            $table->integer('door_count')->nullable();
            $table->integer('luggage_capacity')->nullable();

            $table->integer('passenger_capacity')->nullable();
            $table->boolean('insurance_included')->default(false);
            $table->boolean('free_delivery')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_flash_sale')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['available','not_available'])->default('available');
            $table->foreignId('gear_type_id')->constrained()->onDelete('no action'); //['manual', 'automatic']);
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('no action');
            $table->text('default_image_path')->nullable();
//            $table->unsignedBigInteger('body_style_id')->nullable();
//            $table->unsignedBigInteger('maker_id')->nullable();
//            $table->foreign('body_style_id')->references('id')->on('body_styles')->onDelete('set null');
//            $table->foreign('maker_id')->references('id')->on('makers')->onDelete('set null');

            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
