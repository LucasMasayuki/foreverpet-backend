<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_vermifuges', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('vermifuge_id', 50)->nullable();
            $table->string('vermifuge_other', 128)->nullable();
            $table->string('vermifuge_other_brand', 128)->nullable();
            $table->date('date');
            $table->boolean('applied')->default(false);
            $table->integer('duration')->comment('Duration in days');
            $table->string('lot_number', 16)->nullable();
            $table->integer('notify_before')->default(0);
            $table->boolean('notification_sent')->default(false);
            $table->boolean('next_application_notify_before_sent')->default(false);
            $table->boolean('next_application_notification_sent')->default(false);
            $table->boolean('notify_before_sent')->default(false);
            $table->date('next_application_date')->nullable();
            $table->boolean('next_application_applied')->default(false);
            $table->string('notification_hour', 5)->nullable();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->string('applied_by', 50)->nullable();
            $table->string('next_application_applied_by', 50)->nullable();
            $table->text('notes')->nullable();
            $table->string('vet_name')->nullable();
            $table->string('vet_crmv')->nullable();
            $table->boolean('use_utc')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('vermifuge_id')->references('id')->on('vermifuges')->onDelete('set null');

            $table->index('pet_id');
            $table->index('date');
            $table->index('next_application_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_vermifuges');
    }
};
