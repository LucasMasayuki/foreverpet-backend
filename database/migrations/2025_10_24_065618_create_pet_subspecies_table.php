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
        Schema::create('pet_subspecies', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('species', 50);
            $table->string('name_en', 32);
            $table->string('name_pt', 32);
            $table->string('name_es', 32);
            $table->timestamp('created_at');

            $table->foreign('species')->references('id')->on('pet_species')->onDelete('cascade');
            $table->index('species');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_subspecies');
    }
};
