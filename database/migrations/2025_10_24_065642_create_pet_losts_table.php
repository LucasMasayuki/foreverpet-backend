<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_losts', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('reported_by_user_id', 50)->nullable();
            $table->date('date');
            $table->double('latitude');
            $table->double('longitude');
            $table->boolean('found')->default(false);
            $table->date('found_date')->nullable();
            $table->timestamp('last_notification_sent_at')->nullable();
            $table->string('found_by_user_id', 50)->nullable();
            $table->boolean('last')->default(false)->comment('Most recent lost record for pet');

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('reported_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('found_by_user_id')->references('id')->on('users')->onDelete('set null');

            $table->index('pet_id');
            $table->index('date');
            $table->index('found');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_losts');
    }
};
