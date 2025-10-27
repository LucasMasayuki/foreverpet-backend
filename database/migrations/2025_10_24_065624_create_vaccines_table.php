<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_species_id', 50);
            $table->string('name_en', 32);
            $table->string('name_pt', 32);
            $table->string('name_es', 32);
            $table->text('description');
            $table->boolean('single_dose')->default(false);
            $table->boolean('annual_reinforcement')->default(false);
            $table->boolean('annual_reinforcement_by_first_dose')->default(false);
            $table->boolean('can_be_dismissed')->default(false);
            $table->integer('min_doses')->default(1);
            $table->integer('max_doses')->default(1);
            $table->integer('default_doses')->default(1);
            $table->timestamp('created_at');

            $table->index('pet_species_id');
            $table->index('name_en');
            $table->index('name_pt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccines');
    }
};
