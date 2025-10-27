<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_vaccines', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('vaccine_id', 50);
            $table->string('vaccine_brand_id', 50)->nullable();
            $table->string('vaccine_brand_other', 50)->nullable();
            $table->boolean('dismissed')->default(false);
            $table->timestamp('dismissed_at')->nullable();
            $table->string('dismissed_by', 50)->nullable();
            $table->integer('dose_count')->default(0);
            $table->timestamp('last_notification_check')->nullable();
            $table->date('next_dose_date')->nullable();
            $table->boolean('next_dose_notification_sent')->default(false);
            $table->timestamp('next_dose_before_notification_date')->nullable();
            $table->boolean('next_dose_before_notification_sent')->default(false);
            $table->boolean('some_applied')->default(false);
            $table->string('notification_hour', 5)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('vaccine_id')->references('id')->on('vaccines')->onDelete('cascade');
            $table->foreign('vaccine_brand_id')->references('id')->on('vaccine_brands')->onDelete('set null');

            $table->index('pet_id');
            $table->index('vaccine_id');
            $table->index('dismissed');
            $table->index('next_dose_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_vaccines');
    }
};
