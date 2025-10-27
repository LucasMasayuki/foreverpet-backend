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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('status')->default(1)->comment('1=Ativo, 0=Inativo, 3=Excluido');
            $table->text('areas')->nullable();
            $table->string('theme', 32)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();
            $table->timestamp('last_login_at')->nullable();

            $table->index('email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
