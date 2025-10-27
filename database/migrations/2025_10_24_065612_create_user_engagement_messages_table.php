<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_engagement_messages', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_id', 50)->nullable();
            $table->string('message_key', 16)->nullable();
            $table->string('notification_principal_id', 50)->nullable();
            $table->timestamp('sent_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('message_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_engagement_messages');
    }
};
