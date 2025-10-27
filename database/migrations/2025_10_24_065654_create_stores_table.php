<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('name', 128);
            $table->string('subtitle', 32)->nullable();
            $table->string('description', 128)->nullable();
            $table->string('personal_document', 50)->nullable()->comment('CPF');
            $table->string('company_document', 50)->nullable()->comment('CNPJ');
            $table->string('logo', 256)->nullable();
            $table->string('header_picture', 256)->nullable();
            $table->string('transparent_logo', 256)->nullable();
            $table->string('background_color', 6)->nullable();
            $table->string('working_hours', 256)->nullable();
            $table->string('delivery_methods', 256)->nullable();
            $table->string('picture', 256)->nullable();
            $table->integer('layout_mode')->default(0)->comment('0=Standard, 1=Scroll');
            $table->double('latitude')->default(0);
            $table->double('longitude')->default(0);
            $table->bigInteger('radius')->default(0)->comment('Max delivery radius in meters');
            $table->boolean('fixed_delivery_fee')->default(false);
            $table->double('delivery_fee')->default(0);
            $table->double('minimum_order')->default(0);
            $table->boolean('free_delivery')->default(false);
            $table->double('minimum_order_free_delivery')->default(0);
            $table->boolean('same_day_delivery')->default(false);
            $table->integer('delivery_minutes')->nullable();
            $table->string('same_day_delivery_max_time', 5)->nullable();
            $table->boolean('delivery_on_saturday')->default(false);
            $table->boolean('delivery_on_sunday')->default(false);
            $table->string('max_time_to_delivery', 50)->nullable();
            $table->integer('max_installment_no_interest')->default(1);
            $table->double('comission')->default(0);
            $table->boolean('listed')->default(true);
            $table->text('forced_listed_emails')->nullable();

            // Address
            $table->string('address', 64)->nullable();
            $table->string('address_number', 16)->nullable();
            $table->string('address_complement', 16)->nullable();
            $table->string('address_neighborhood', 48)->nullable();
            $table->string('address_city', 48)->nullable();
            $table->string('address_state', 32)->nullable();
            $table->string('address_postal_code', 32)->nullable();

            $table->string('email', 128)->nullable();
            $table->string('phone_numbers', 128)->nullable();
            $table->string('site', 256)->nullable();
            $table->string('admin_email', 128)->nullable();
            $table->string('admin_password', 128)->nullable();
            $table->string('notification_emails', 512)->nullable();
            $table->string('notification_phone_numbers', 256)->nullable();
            $table->string('available_cities', 512)->nullable();
            $table->string('visible_cities', 512)->nullable();
            $table->decimal('stars_average', 3, 2)->default(0);
            $table->bigInteger('featured_order')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->index('listed');
            $table->index('address_city');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
