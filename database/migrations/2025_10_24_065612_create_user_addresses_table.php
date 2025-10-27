<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_id', 50);
            $table->string('address', 64);
            $table->string('address_number', 16)->nullable();
            $table->string('address_complement', 16)->nullable();
            $table->string('address_neighborhood', 48)->nullable();
            $table->string('address_city', 48);
            $table->string('address_state', 32)->nullable();
            $table->string('address_state_other', 32)->nullable();
            $table->string('address_country', 32);
            $table->string('address_postal_code', 32)->nullable();
            $table->double('latitude')->default(0);
            $table->double('longitude')->default(0);
            $table->boolean('removed')->default(false);
            $table->timestamp('removed_at')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('source_id', 50)->nullable();
            $table->timestamp('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('removed');
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
