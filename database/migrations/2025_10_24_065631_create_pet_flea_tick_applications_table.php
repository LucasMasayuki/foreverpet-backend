<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_flea_tick_applications', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('flea_tick_protection_id', 50)->nullable();
            $table->string('flea_tick_protection_other', 128)->nullable();
            $table->string('flea_tick_protection_other_brand', 128)->nullable();
            $table->date('date');
            $table->boolean('applied')->default(false);
            $table->integer('duration')->comment('Duration in days');
            $table->string('lot_number', 16)->nullable();
            $table->date('next_application_date');
            $table->integer('notify_before')->default(0);
            $table->boolean('notify_before_sent')->default(false);
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamp('notify_before_sent_at')->nullable();
            $table->string('notification_hour', 5)->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('applied_by', 50)->nullable();
            $table->integer('type')->default(0)->comment('0=medication, 1=collar');
            $table->text('notes')->nullable();
            $table->string('vet_name')->nullable();
            $table->string('vet_crmv')->nullable();
            $table->boolean('use_utc')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('flea_tick_protection_id')->references('id')->on('flea_tick_protections')->onDelete('set null');

            $table->index('pet_id');
            $table->index('date');
            $table->index('next_application_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_flea_tick_applications');
    }
};
