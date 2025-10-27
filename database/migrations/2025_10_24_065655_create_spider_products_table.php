<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spider_products', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('brand', 64)->nullable();
            $table->string('name', 128);
            $table->string('bar_code', 32)->nullable();
            $table->string('section', 256);
            $table->string('category', 256);
            $table->string('species', 50)->nullable();
            $table->string('site', 4000)->comment('Source website');
            $table->text('url')->nullable();
            $table->integer('run')->default(0);
            $table->text('pictures')->nullable();
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('breadcrumbs')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('last_update_at')->nullable();

            $table->index('bar_code');
            $table->index('species');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spider_products');
    }
};
