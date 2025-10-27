<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ongs', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('category', 32);
            $table->string('name', 64);
            $table->string('document_number', 32)->nullable()->comment('CNPJ');
            $table->text('description')->nullable();
            $table->string('address', 128)->nullable();
            $table->string('city', 32)->nullable();
            $table->string('state', 32)->nullable();
            $table->string('state_other', 32)->nullable();
            $table->string('country', 32)->nullable();
            $table->string('postal_code', 32)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('phone_numbers', 128)->nullable();
            $table->string('site', 256)->nullable();
            $table->string('picture', 512)->nullable();
            $table->string('logo', 512)->nullable();
            $table->integer('featured_order')->default(0);
            $table->string('species')->nullable()->comment('Animais aceitos');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->boolean('enabled')->default(true);
            $table->string('password')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('enable_user')->default(false);
            $table->string('theme', 32)->nullable();
            $table->string('app_user_emails')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->index('category');
            $table->index('enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ongs');
    }
};
