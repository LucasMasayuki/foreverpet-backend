<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('place_qualifications', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('place_id', 50)->nullable();
            $table->string('google_place_id')->nullable();
            $table->string('google_place_name')->nullable();
            $table->double('google_place_latitude')->nullable();
            $table->double('google_place_longitude')->nullable();
            $table->string('user_id', 50);
            $table->decimal('stars_attendance', 3, 2)->nullable();
            $table->decimal('stars_location', 3, 2)->nullable();
            $table->decimal('stars_products_services', 3, 2)->nullable();
            $table->decimal('stars_average', 3, 2)->nullable();
            $table->boolean('favorite')->default(false);
            $table->string('comment', 256)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('place_id');
            $table->index('google_place_id');
            $table->index('user_id');
            $table->index('favorite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('place_qualifications');
    }
};
