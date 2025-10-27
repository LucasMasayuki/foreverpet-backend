<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('google_place_id')->nullable();
            $table->string('category');
            $table->string('name');
            $table->string('address', 128)->nullable();
            $table->string('city', 32)->nullable();
            $table->string('state', 32)->nullable();
            $table->string('state_other', 32)->nullable();
            $table->string('country', 32)->nullable();
            $table->string('postal_code', 32)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('phone_numbers', 128)->nullable();
            $table->string('site', 256)->nullable();
            $table->string('booking_url', 256)->nullable();
            $table->text('description')->nullable();
            $table->string('picture')->nullable();
            $table->string('logo')->nullable();
            $table->integer('featured_order')->default(0);
            $table->string('species')->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->decimal('stars_attendance', 3, 2)->default(0);
            $table->decimal('stars_location', 3, 2)->default(0);
            $table->decimal('stars_products_services', 3, 2)->default(0);
            $table->decimal('stars_average', 3, 2)->default(0);
            $table->text('hours')->nullable()->comment('Operating hours');
            $table->boolean('enabled')->default(true);
            $table->string('created_by', 50)->nullable();
            $table->timestamp('enabled_at')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->index('google_place_id');
            $table->index('category');
            $table->index('enabled');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
