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
        Schema::create('vets', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('type')->comment('Vet, Breeder, Clinic, Caregiver, Other');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('picture', 512)->nullable();
            $table->string('crmv', 64)->nullable()->comment('CRMV/CBKC');
            $table->string('phone_number', 32)->nullable();
            $table->string('celphone_number', 32)->nullable();
            $table->string('address', 64)->nullable();
            $table->string('address_number', 16)->nullable();
            $table->string('address_complement', 16)->nullable();
            $table->string('address_neighborhood', 32)->nullable();
            $table->string('address_city', 32)->nullable();
            $table->string('address_state', 32)->nullable();
            $table->string('address_state_other', 32)->nullable();
            $table->string('address_country', 32)->nullable();
            $table->string('address_postal_code', 32)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->index('type');
            $table->index('address_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vets');
    }
};
