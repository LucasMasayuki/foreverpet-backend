<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('store_id', 50);
            $table->string('category_id', 50)->nullable();
            $table->string('internal_code', 50)->nullable();
            $table->string('bar_code', 32);
            $table->string('brand', 64);
            $table->string('name', 256);
            $table->decimal('price', 10, 2);
            $table->decimal('price_in_store', 10, 2);
            $table->decimal('cost', 10, 2)->nullable();
            $table->boolean('best_seller')->default(false);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->text('pictures')->nullable();
            $table->bigInteger('stock')->default(0);
            $table->bigInteger('featured_order')->default(0);
            $table->boolean('disabled')->default(false);
            $table->bigInteger('width')->nullable();
            $table->bigInteger('height')->nullable();
            $table->bigInteger('length')->nullable();
            $table->bigInteger('weight')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('store_product_categories')->onDelete('set null');

            $table->index('store_id');
            $table->index('category_id');
            $table->index('bar_code');
            $table->index('disabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
