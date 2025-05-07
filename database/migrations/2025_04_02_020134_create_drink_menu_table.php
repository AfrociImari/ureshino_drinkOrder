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
        Schema::create('drink_menu', function (Blueprint $table) {
            $table->id('menu_id');
            $table->foreignId('category_id')->constrained('drink_category')->onDelete('cascade'); // Foreign key to category
            $table->string('product_name');//生ビール
            $table->string('variant');//大　中　小
            $table->string('unitprice');//
            $table->string('image');//
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drink_menu');
    }
};
