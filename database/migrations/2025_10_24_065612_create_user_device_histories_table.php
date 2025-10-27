<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_device_histories', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_device_id', 50);
            $table->double('latitude');
            $table->double('longitude');
            $table->bigInteger('update_count')->default(0);
            $table->integer('app_version')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at');

            $table->foreign('user_device_id')->references('id')->on('user_devices')->onDelete('cascade');
            $table->index('user_device_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_device_histories');
    }
};
