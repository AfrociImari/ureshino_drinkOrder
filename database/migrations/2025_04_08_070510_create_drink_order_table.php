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
        Schema::create('drink_order', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('checkin_id');  // Must match data type of 'checkin_id' from check_in table
            $table->foreign('checkin_id')->references('checkin_id')->on('check_in')->onDelete('cascade');
            $table->string('date',25);//
            $table->string('type',25);//
            $table->string('product_name',50);//
            $table->string('variant',50);//
            $table->string('quantity',25);//
            $table->string('amount',25);//
            $table->string('served_flg',25);//配膳済フラグ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drink_order');
    }
};
