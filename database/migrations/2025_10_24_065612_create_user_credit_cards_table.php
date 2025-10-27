<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_credit_cards', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_id', 50);
            $table->string('name', 256)->nullable()->comment('Nickname');
            $table->string('token', 512);
            $table->string('holder', 256);
            $table->string('brand', 50);
            $table->string('last_digits', 4);
            $table->integer('expiration_month');
            $table->integer('expiration_year');
            $table->string('birthdate', 10)->nullable();
            $table->string('holder_name', 256);
            $table->string('document_number', 32)->comment('CPF');
            $table->string('phone_country_code', 8)->nullable();
            $table->string('phone_number', 32)->nullable();

            // Billing address
            $table->string('address', 64)->nullable();
            $table->string('address_number', 16)->nullable();
            $table->string('address_complement', 16)->nullable();
            $table->string('address_neighborhood', 48)->nullable();
            $table->string('address_city', 48)->nullable();
            $table->string('address_state', 32)->nullable();
            $table->string('address_state_other', 32)->nullable();
            $table->string('address_country', 32)->nullable();
            $table->string('address_postal_code', 32)->nullable();

            $table->boolean('removed')->default(false);
            $table->timestamp('removed_at')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamp('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('removed');
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_credit_cards');
    }
};
