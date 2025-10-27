<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_id', 50);
            $table->string('device_uuid', 50);
            $table->string('firebase_token', 256)->nullable();
            $table->boolean('logged_in')->default(true);
            $table->string('language', 10)->nullable();
            $table->double('latitude')->default(0);
            $table->double('longitude')->default(0);
            $table->string('platform', 32)->nullable()->comment('iOS, Android');
            $table->boolean('unregistered')->default(false);
            $table->timestamp('unregistered_at')->nullable();
            $table->integer('app_version')->nullable();
            $table->string('advertisement_id')->nullable();
            $table->boolean('advertisement_tracking_limited')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('device_uuid');
            $table->index('logged_in');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
