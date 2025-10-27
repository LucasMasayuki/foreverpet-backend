<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_scheduled_events', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('module', 50)->comment('Module name: vaccine, medication, exam, etc');
            $table->string('item_id', 50)->comment('ID of related item in module');
            $table->string('item_title', 256)->nullable();
            $table->integer('item_type')->nullable()->comment('Type within module');
            $table->date('date');
            $table->timestamp('created_at');

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->index('pet_id');
            $table->index('module');
            $table->index('date');
            $table->index(['pet_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_scheduled_events');
    }
};
