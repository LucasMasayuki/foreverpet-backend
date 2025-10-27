<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_vaccine_doses', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('pet_vaccine_id', 50);
            $table->string('vaccine_dose_id', 50)->nullable();
            $table->string('vaccine_brand_id', 50)->nullable();
            $table->integer('dose_year')->nullable();
            $table->date('suggested_date');
            $table->boolean('applied')->default(false);
            $table->string('applied_by', 50)->nullable();
            $table->date('applied_date')->nullable();
            $table->boolean('will_not_be_applied')->default(false);
            $table->string('will_not_be_applied_by', 50)->nullable();
            $table->date('will_not_be_applied_date')->nullable();
            $table->text('will_not_be_applied_reason')->nullable();
            $table->string('lot_number')->nullable();
            $table->boolean('has_diluent')->default(false);
            $table->string('diluent_lot_number')->nullable();
            $table->string('vet_name')->nullable();
            $table->string('vet_crmv')->nullable();
            $table->string('vaccine_brand_other')->nullable();
            $table->string('picture')->nullable();
            $table->timestamp('last_notification_sent_at')->nullable();
            $table->boolean('remember')->default(false);
            $table->integer('remember_before_days')->default(0);
            $table->integer('remember_period')->default(0);
            $table->integer('remember_period_type')->default(0)->comment('0=days, 1=hours, 2=minutes, 3=weeks');
            $table->text('notes')->nullable();
            $table->boolean('use_utc')->default(false);
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('pet_vaccine_id')->references('id')->on('pet_vaccines')->onDelete('cascade');
            $table->foreign('vaccine_dose_id')->references('id')->on('vaccine_doses')->onDelete('set null');
            $table->foreign('vaccine_brand_id')->references('id')->on('vaccine_brands')->onDelete('set null');

            $table->index('pet_vaccine_id');
            $table->index('applied');
            $table->index('suggested_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_vaccine_doses');
    }
};
