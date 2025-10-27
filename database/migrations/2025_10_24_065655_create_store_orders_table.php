<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_orders', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('user_id', 50);
            $table->string('store_id', 50);
            $table->string('store_name', 128);
            $table->string('store_logo', 256);
            $table->string('card_id', 50);
            $table->string('delivery_address_id', 50);
            $table->string('sender_hash', 128);
            $table->string('session_id', 128);
            $table->string('transaction_code', 128);
            $table->string('transaction_status', 128);
            $table->timestamp('transaction_status_date');
            $table->integer('installments')->default(1);
            $table->decimal('installment_value', 10, 2);
            $table->decimal('delivery_fee', 10, 2);
            $table->bigInteger('shipping_option_id')->nullable();
            $table->string('shipping_option_name')->nullable();
            $table->text('shipping_option_content')->nullable();
            $table->string('shipping_payment_id')->nullable();
            $table->text('shipping_payment_url')->nullable();
            $table->text('shipping_tag_url')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status')->comment('WAITING_PAYMENT|PAYMENT_PROCESSING|PAID|ACCEPTED|IN_TRANSIT|FINISHED|REJECTED');
            $table->string('rejected_reason', 256)->nullable();
            $table->string('waiting_payment_reason', 256)->nullable();
            $table->timestamp('status_date');
            $table->text('transaction_notifications')->nullable();
            $table->string('source')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('user_credit_cards')->onDelete('cascade');
            $table->foreign('delivery_address_id')->references('id')->on('user_addresses')->onDelete('cascade');

            $table->index('user_id');
            $table->index('store_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_orders');
    }
};
