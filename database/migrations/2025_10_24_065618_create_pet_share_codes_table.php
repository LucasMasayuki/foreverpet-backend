<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_share_codes', function (Blueprint $table) {
            $table->string('id', 50)->primary()->comment('This ID is also the share code');
            $table->string('pet_id', 50);
            $table->timestamp('end_date')->nullable()->comment('Null for infinite');
            $table->boolean('read_only')->default(false);
            $table->boolean('can_reshare')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->timestamp('created_at');

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->index('pet_id');
            $table->index('used_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_share_codes');
    }
};
