<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_product_categories', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('parent_id', 50)->nullable();
            $table->string('section_id', 50)->nullable();
            $table->string('store_id', 50)->nullable();
            $table->string('name', 50);
            $table->integer('sort_order')->default(0);
            $table->boolean('show_in_menu')->default(true);
            $table->boolean('highlighted')->default(false);
            $table->string('picture', 256)->nullable();
            $table->timestamp('created_at');

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('store_product_category_sections')->onDelete('set null');
            $table->index('store_id');
            $table->index('section_id');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_product_categories');
    }
};
