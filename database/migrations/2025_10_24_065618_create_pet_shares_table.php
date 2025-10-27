<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_shares', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('user_id', 50)->comment('User with whom the pet is shared');
            $table->timestamp('end_date')->nullable()->comment('Null for infinite');
            $table->boolean('read_only')->default(false);
            $table->boolean('can_reshare')->default(false);
            $table->boolean('show_in_pet_list')->default(false);
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('pet_id');
            $table->index('user_id');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_shares');
    }
};
