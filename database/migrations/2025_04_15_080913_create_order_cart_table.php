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
        Schema::create('order_cart', function (Blueprint $table) {
            $table->id();
            $table->string('checkin_id',25);//
            $table->string('product_name',50);//
            $table->string('variant',50);//
            $table->string('unitprice',25);//
            $table->string('quantity',25);//
            $table->string('total_amount',25);//
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_cart');
    }
};
