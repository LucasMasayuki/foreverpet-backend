<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_picture_pets', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_picture_id', 50);
            $table->string('pet_id', 50);

            $table->foreign('user_picture_id')->references('id')->on('user_pictures')->onDelete('cascade');
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->index('user_picture_id');
            $table->index('pet_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_picture_pets');
    }
};
