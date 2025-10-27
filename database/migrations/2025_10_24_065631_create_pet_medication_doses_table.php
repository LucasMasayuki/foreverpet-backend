<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_medication_doses', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_medication_id', 50);
            $table->boolean('applied')->nullable();
            $table->timestamp('date');
            $table->boolean('manual_date')->default(false);
            $table->integer('day_number')->default(1);
            $table->string('applied_by', 50)->nullable();
            $table->boolean('use_utc')->default(false);

            $table->foreign('pet_medication_id')->references('id')->on('pet_medications')->onDelete('cascade');
            $table->index('pet_medication_id');
            $table->index('date');
            $table->index('applied');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_medication_doses');
    }
};
