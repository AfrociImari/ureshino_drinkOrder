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
        Schema::create('check_in', function (Blueprint $table) {
            $table->bigIncrements('checkin_id');  // Explicitly set primary key as 'checkin_id' 
            $table->unsignedBigInteger('room_id');  // Must match data type of 'room_id'
            $table->foreign('room_id')->references('room_id')->on('room')->onDelete('cascade');
            $table->string('date', 50);
            $table->string('timing', 50);
            $table->string('type', 45);//breakfast,lunch,dinner
            $table->string('qr_code', 2048)->nullable();// Max length for URLs in browsers
            $table->string('order_stop', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in');
    }
};