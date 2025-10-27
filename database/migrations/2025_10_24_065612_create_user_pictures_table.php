<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_pictures', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_id', 50);
            $table->string('picture_url', 256);
            $table->string('original_picture_url', 256);
            $table->text('notes')->nullable();
            $table->date('date');
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('date');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_pictures');
    }
};
