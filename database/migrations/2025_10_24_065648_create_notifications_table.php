<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('principal_id', 50)->comment('Correlates all notifications in a sending');
            $table->string('user_id', 50);
            $table->string('title', 512)->nullable();
            $table->string('body', 512)->nullable();
            $table->string('screen', 50)->nullable();
            $table->string('pet_id', 50)->nullable();
            $table->string('item_id', 50)->nullable();
            $table->string('sub_id', 50)->nullable();
            $table->boolean('read_only')->default(false);
            $table->boolean('is_from_share')->default(false);
            $table->text('text')->nullable();
            $table->string('picture_url', 512)->nullable();
            $table->string('link', 512)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('opened_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');

            $table->index('user_id');
            $table->index('pet_id');
            $table->index('principal_id');
            $table->index('opened_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
