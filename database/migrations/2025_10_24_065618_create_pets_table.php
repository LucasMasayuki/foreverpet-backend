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
        Schema::create('pets', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_id', 50);
            $table->string('name', 256);
            $table->string('species', 50)->comment('Dog, Cat, Fish, Bird, Reptile, Rodent, Ferret, Other');
            $table->string('subspecies', 50)->nullable();
            $table->string('race', 50);
            $table->string('gender', 16)->comment('Male, Female');
            $table->string('color', 256)->nullable();
            $table->date('birthdate')->nullable();

            // Adoption info
            $table->boolean('adopted')->default(false);
            $table->date('adopted_at')->nullable();
            $table->boolean('use_vaccines_from_adoption_date')->default(false);

            // Identification
            $table->string('chip_number', 32)->nullable();
            $table->string('pedigree_number', 32)->nullable();

            // Physical characteristics
            $table->string('pelage', 256)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->date('weight_date')->nullable();
            $table->boolean('castrated')->default(false);
            $table->date('castrated_at')->nullable();
            $table->string('picture', 256)->nullable();

            // Professional/ONG pets
            $table->boolean('is_from_vet')->default(false);
            $table->boolean('is_from_ong')->default(false);
            $table->string('owner_name')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_phone_number')->nullable();

            // Status
            $table->boolean('is_lost')->default(false);
            $table->boolean('available_to_donation')->default(false);
            $table->boolean('deceased')->default(false);
            $table->date('deceased_at')->nullable();
            $table->boolean('deceased_mail_sent')->default(false);
            $table->timestamp('deceased_mail_sent_at')->nullable();

            // Notes and versioning
            $table->text('notes')->nullable();
            $table->string('version', 14)->nullable();
            $table->boolean('use_utc')->default(false);

            // Timestamps
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('scheduled_events_updated_at')->nullable();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('species');
            $table->index('is_from_vet');
            $table->index('is_from_ong');
            $table->index('is_lost');
            $table->index('available_to_donation');
            $table->index('deceased');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
