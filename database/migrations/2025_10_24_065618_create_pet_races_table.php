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
        Schema::create('pet_races', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('species', 50);
            $table->string('subspecies', 50)->nullable();
            $table->string('name_en', 64);
            $table->string('name_pt', 64);
            $table->string('name_es', 64);
            $table->timestamp('created_at');

            $table->index('species');
            $table->index('subspecies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_races');
    }
};
