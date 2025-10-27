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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('vet_id', 50)->nullable();
            $table->string('ong_id', 50)->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('picture', 512)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender', 16)->nullable();
            $table->string('phone_number_country_code', 8)->nullable();
            $table->string('phone_number', 32)->nullable();

            // Address fields
            $table->string('address', 64)->nullable();
            $table->string('address_number', 16)->nullable();
            $table->string('address_complement', 16)->nullable();
            $table->string('address_neighborhood', 48)->nullable();
            $table->string('address_city', 48)->nullable();
            $table->string('address_state', 32)->nullable();
            $table->string('address_state_other', 32)->nullable();
            $table->string('address_country', 32)->nullable();
            $table->string('address_postal_code', 32)->nullable();

            // Presumed address fields
            $table->string('presumed_address', 64)->nullable();
            $table->string('presumed_address_number', 16)->nullable();
            $table->string('presumed_address_complement', 16)->nullable();
            $table->string('presumed_address_neighborhood', 48)->nullable();
            $table->string('presumed_address_city', 48)->nullable();
            $table->string('presumed_address_state', 32)->nullable();
            $table->string('presumed_address_state_other', 32)->nullable();
            $table->string('presumed_address_country', 32)->nullable();
            $table->string('presumed_address_postal_code', 32)->nullable();
            $table->boolean('presumed_address_filled_by_user')->default(false);

            // Status and flags
            $table->boolean('internal')->default(false);
            $table->boolean('beta_tester')->default(false);
            $table->integer('status')->default(0)->comment('1=Ativo, 0=Inativo, 2=Descadastrado, 3=Removido');
            $table->string('password_reset_token')->nullable();
            $table->boolean('register_complete')->default(false);

            // Timestamps
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_visit_at')->nullable();
            $table->string('last_ip', 256)->nullable();

            // OAuth IDs
            $table->string('facebook_id', 128)->nullable();
            $table->string('google_id', 128)->nullable();
            $table->string('apple_id', 128)->nullable();
            $table->string('twitter_id', 128)->nullable();

            // Terms and privacy
            $table->boolean('terms_and_conditions_accepted')->default(false);
            $table->timestamp('terms_and_conditions_accepted_date')->nullable();
            $table->boolean('privacy_accepted')->default(false);
            $table->timestamp('privacy_accepted_date')->nullable();

            // Profile and verification
            $table->integer('profile_version')->default(0);
            $table->string('qr_code_login_key')->nullable();
            $table->string('phone_number_confirmation_code', 16)->nullable();
            $table->boolean('phone_number_confirmed')->default(false);
            $table->string('phone_number_confirmation_code_sent_to', 32)->nullable();
            $table->timestamp('phone_number_confirmation_code_sent_at')->nullable();

            // User management
            $table->boolean('removed')->default(false);
            $table->timestamp('removed_at')->nullable();
            $table->string('removed_reason', 256)->nullable();
            $table->boolean('logoff_required')->default(false);
            $table->boolean('is_pro_user')->default(false);

            // Challenge codes
            $table->string('email_challenge_code', 6)->nullable();
            $table->timestamp('email_challenge_code_sent_at')->nullable();
            $table->string('sms_challenge_code', 6)->nullable();
            $table->timestamp('sms_challenge_code_sent_at')->nullable();

            // Additional flags
            $table->boolean('inactive')->default(false);
            $table->string('feature_flags', 512)->nullable();
            $table->boolean('block_phone')->default(false);
            $table->string('block_phone_reason', 256)->nullable();

            // Foreign keys
            $table->foreign('vet_id')->references('id')->on('vets')->onDelete('set null');
            $table->foreign('ong_id')->references('id')->on('ongs')->onDelete('set null');

            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index('phone_number');
            $table->index('facebook_id');
            $table->index('google_id');
            $table->index('apple_id');
            $table->index('twitter_id');
            $table->index('vet_id');
            $table->index('ong_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
