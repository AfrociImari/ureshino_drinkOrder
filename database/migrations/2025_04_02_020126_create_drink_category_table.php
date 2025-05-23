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
        Schema::create('drink_category', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');// e.g., Beer, Highball, Soft Drink
            $table->string('parent_category');//Alcohol,softdrink,tea
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drink_category');
    }
};
