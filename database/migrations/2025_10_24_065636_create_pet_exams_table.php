<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_exams', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_id', 50);
            $table->string('vet_id', 50)->nullable();
            $table->string('vet_name', 50)->nullable();
            $table->string('vet_crmv', 50)->nullable();
            $table->date('date');
            $table->text('orientations')->nullable();
            $table->date('return_date')->nullable();
            $table->text('files')->nullable()->comment('JSON array of file URLs');
            $table->integer('type')->default(0)->comment('0=Consultation, 1=Exam');
            $table->integer('notify_before')->default(0);
            $table->string('notification_hour', 5)->nullable();
            $table->boolean('done')->default(false);
            $table->boolean('return_done')->default(false);
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->boolean('use_utc')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->index('pet_id');
            $table->index('date');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_exams');
    }
};
