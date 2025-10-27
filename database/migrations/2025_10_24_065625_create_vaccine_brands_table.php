<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaccine_brands', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('vaccine_id', 50);
            $table->string('name');
            $table->string('vendor');

            $table->foreign('vaccine_id')->references('id')->on('vaccines')->onDelete('cascade');
            $table->index('vaccine_id');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccine_brands');
    }
};
