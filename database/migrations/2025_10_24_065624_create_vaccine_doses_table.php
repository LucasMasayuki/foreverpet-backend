<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vaccine_doses', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('vaccine_id', 50);
            $table->integer('dose_number');
            $table->integer('suggested_days')->comment('Days after birth or adoption');

            $table->foreign('vaccine_id')->references('id')->on('vaccines')->onDelete('cascade');
            $table->index('vaccine_id');
            $table->index('dose_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vaccine_doses');
    }
};
