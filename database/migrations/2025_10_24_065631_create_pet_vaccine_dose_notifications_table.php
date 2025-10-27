<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_vaccine_dose_notifications', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_vaccine_dose_id', 50);
            $table->string('user_device_id', 50);
            $table->timestamp('sent_at');
            $table->boolean('dismissed')->default(false);

            $table->foreign('pet_vaccine_dose_id')->references('id')->on('pet_vaccine_doses')->onDelete('cascade');
            $table->index('pet_vaccine_dose_id');
            $table->index('user_device_id');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_vaccine_dose_notifications');
    }
};
