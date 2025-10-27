<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_heats', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('finished')->default(false);
            $table->integer('notify_before_days')->default(0);
            $table->date('notification_date')->nullable();
            $table->string('notification_hour', 5)->nullable();
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('notification_sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->boolean('use_utc')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->index('pet_id');
            $table->index('start_date');
            $table->index('notification_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_heats');
    }
};
