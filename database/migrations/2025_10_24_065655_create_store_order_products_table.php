<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_order_products', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('order_id', 50);
            $table->string('product_id', 50);
            $table->string('product_name', 256);
            $table->integer('count');
            $table->decimal('price', 10, 2);

            $table->foreign('order_id')->references('id')->on('store_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('store_products')->onDelete('cascade');

            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_order_products');
    }
};
