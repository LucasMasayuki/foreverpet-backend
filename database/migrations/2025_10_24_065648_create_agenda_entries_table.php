<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_entries', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('name', 50)->nullable();
            $table->string('local', 50)->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->text('notes')->nullable();
            $table->string('color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('type')->nullable();
            $table->text('pictures')->nullable()->comment('JSON array of picture URLs');
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->index('pet_id');
            $table->index('start_datetime');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_entries');
    }
};
