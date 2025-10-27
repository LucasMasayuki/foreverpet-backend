<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ong_adoption_contacts', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_id', 50);
            $table->string('ong_id', 50);
            $table->string('pet_id', 50);
            $table->string('name', 256);
            $table->string('email', 256);
            $table->string('phone_number', 32);
            $table->timestamp('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ong_id')->references('id')->on('ongs')->onDelete('cascade');
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');

            $table->index('user_id');
            $table->index('ong_id');
            $table->index('pet_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ong_adoption_contacts');
    }
};
