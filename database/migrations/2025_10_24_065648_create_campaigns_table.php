<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('name', 64);
            $table->integer('type')->comment('0=push, 1=ad');
            $table->string('title', 64)->nullable();
            $table->string('subtitle', 256)->nullable();
            $table->text('text')->nullable();
            $table->string('picture_url', 256)->nullable();
            $table->string('link', 512)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->bigInteger('max_ad_views')->nullable();
            $table->bigInteger('view_count')->default(0);
            $table->boolean('disabled')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->index('type');
            $table->index('disabled');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
