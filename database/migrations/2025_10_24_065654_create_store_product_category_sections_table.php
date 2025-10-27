<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_product_category_sections', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('name', 50);
            $table->integer('sort_order')->default(0);
            $table->string('picture', 256)->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_product_category_sections');
    }
};
