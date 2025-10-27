<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_hygienes', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->date('date');
            $table->integer('type')->comment('Type of hygiene procedure');
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->boolean('use_utc')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->index('pet_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_hygienes');
    }
};
