<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_sendings', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('campaign_id', 50);
            $table->integer('filter_type')->comment('-1=All, 0=Emails, 1=Location');
            $table->text('filter_value')->nullable();
            $table->integer('status')->default(0)->comment('0=Queued, 1=Sending, 2=Completed');
            $table->boolean('ignore_users_already_received')->default(false);
            $table->double('latitude')->nullable();
            $table->double('latitude_min')->nullable();
            $table->double('latitude_max')->nullable();
            $table->double('longitude')->nullable();
            $table->double('longitude_min')->nullable();
            $table->double('longitude_max')->nullable();
            $table->integer('radius')->nullable()->comment('Radius in meters');
            $table->string('pet_species')->nullable();
            $table->string('pet_race')->nullable();
            $table->string('pet_gender')->nullable();
            $table->text('emails')->nullable();
            $table->string('screens', 500)->nullable();
            $table->integer('minimum_app_version')->nullable();
            $table->string('cities', 2000)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('sent_at')->nullable();

            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->index('campaign_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_sendings');
    }
};
