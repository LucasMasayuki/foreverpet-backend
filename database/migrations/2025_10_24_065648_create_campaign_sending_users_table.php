<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_sending_users', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('campaign_id', 50);
            $table->string('campaign_sending_id', 50);
            $table->string('user_id', 50);
            $table->string('user_device_id', 50);
            $table->boolean('sent_successfully')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->boolean('viewed')->default(false);
            $table->timestamp('viewed_at')->nullable();
            $table->boolean('clicked')->default(false);
            $table->timestamp('clicked_at')->nullable();
            $table->bigInteger('clicked_read_time')->default(0);
            $table->boolean('closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->bigInteger('closed_read_time')->default(0);
            $table->bigInteger('view_count')->default(0);

            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('campaign_sending_id')->references('id')->on('campaign_sendings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('campaign_id');
            $table->index('campaign_sending_id');
            $table->index('user_id');
            $table->index('viewed');
            $table->index('clicked');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_sending_users');
    }
};
