<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_lost_notifications', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_lost_id', 50);
            $table->string('user_device_id', 50);
            $table->date('date');
            $table->boolean('found_sent')->default(false);

            $table->foreign('pet_lost_id')->references('id')->on('pet_losts')->onDelete('cascade');
            $table->index('pet_lost_id');
            $table->index('user_device_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_lost_notifications');
    }
};
