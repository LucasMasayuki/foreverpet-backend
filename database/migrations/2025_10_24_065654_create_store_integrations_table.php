<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_integrations', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('store_id', 50);
            $table->string('integration')->comment('pagseguro, melhorenvio, pex');
            $table->boolean('sandbox')->default(false);
            $table->string('client_id', 256)->nullable();
            $table->string('client_secret', 2048)->nullable();
            $table->text('authorization_code')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('last_update_at')->nullable();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->index('store_id');
            $table->index('integration');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_integrations');
    }
};
