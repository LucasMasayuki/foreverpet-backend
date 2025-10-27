<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flea_tick_protections', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('brand', 64);
            $table->string('name', 128);
            $table->string('bar_code', 32)->nullable();
            $table->string('picture', 256)->nullable();
            $table->integer('duration')->nullable()->comment('Duration in days');
            $table->integer('species')->nullable()->comment('1=Dog, 2=Cat, 3=Both');
            $table->integer('type')->default(0)->comment('0=medication, 1=collar');

            $table->index('brand');
            $table->index('name');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flea_tick_protections');
    }
};
