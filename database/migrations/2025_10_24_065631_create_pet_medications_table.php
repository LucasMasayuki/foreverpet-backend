<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_medications', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('medication_id', 50)->nullable();
            $table->string('medication_other', 128)->nullable();
            $table->string('medication_other_brand', 128)->nullable();
            $table->string('store_name', 128)->nullable();
            $table->boolean('is_compounded')->default(false);
            $table->string('category', 50)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('continued')->default(false);
            $table->integer('notify_before')->default(0);
            $table->boolean('notify_before_sent')->default(false);
            $table->timestamp('next_notification_date')->nullable();
            $table->decimal('dose', 10, 2)->default(0);
            $table->string('dose_value')->nullable();
            $table->string('dose_type', 16)->nullable();
            $table->integer('periodicity');
            $table->string('periodicity_type', 16)->comment('hour, day, month, year');
            $table->string('lot_number', 16)->nullable();
            $table->boolean('consider_old_doses_applied')->default(false);
            $table->text('notes')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->boolean('suspended')->default(false);
            $table->string('suspended_by', 50)->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->string('suspended_reason', 256)->nullable();
            $table->boolean('use_utc')->default(false);
            $table->string('vet_name')->nullable();
            $table->string('vet_crmv')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('medication_id')->references('id')->on('medications')->onDelete('set null');

            $table->index('pet_id');
            $table->index('medication_id');
            $table->index('start_date');
            $table->index('suspended');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_medications');
    }
};
