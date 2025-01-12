<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blog_cars', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->dropForeign(['blog_id']);
    
            $table->foreign('car_id')
                  ->references('id')->on('cars')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
    
            $table->foreign('blog_id')
                  ->references('id')->on('blogs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('your_table_name', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
            $table->dropForeign(['blog_id']);
    
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
        });
    }
    
};
