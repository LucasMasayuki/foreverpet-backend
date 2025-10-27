<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('brand', 64);
            $table->string('name', 256);
            $table->string('bar_code', 32);
            $table->string('species', 50)->nullable();
            $table->integer('specie_flags')->default(0);
            $table->string('picture', 256)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('last_update_at')->nullable();

            $table->index('brand');
            $table->index('name');
            $table->index('bar_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
