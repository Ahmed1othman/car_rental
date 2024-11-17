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
        Schema::table('cars', function (Blueprint $table) {
            $table->integer('daily_mileage_included')->nullable()->after('monthly_discount_price'); // Adjust 'some_column' as needed
            $table->integer('weekly_mileage_included')->nullable()->after('daily_mileage_included');
            $table->integer('monthly_mileage_included')->nullable()->after('weekly_mileage_included');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('daily_mileage_included');
            $table->dropColumn('weekly_mileage_included');
            $table->dropColumn('monthly_mileage_included');
        });
    }
};
