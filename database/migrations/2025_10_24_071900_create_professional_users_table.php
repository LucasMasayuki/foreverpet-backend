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
        Schema::create('professional_users', function (Blueprint $table) {
            $table->id();

            // Autenticação
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');

            // Tipo de profissional
            $table->enum('professional_type', [
                'admin',      // Administrador interno
                'vet',        // Veterinário/Clínica
                'ong',        // ONG
                'staff',      // Equipe (atendente, recepcionista)
                'manager'     // Gerente
            ]);

            // Referência ao perfil específico (polymorphic)
            $table->string('professional_id', 50)->nullable()->comment('ID da tabela relacionada (vets, ongs, etc)');
            $table->string('professional_table')->nullable()->comment('Nome da tabela relacionada');

            // Status
            $table->enum('status', [
                'active',
                'inactive',
                'suspended',
                'pending_approval'
            ])->default('pending_approval');

            // Segurança
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();

            // Auditoria
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->text('last_user_agent')->nullable();

            // Metadados
            $table->json('preferences')->nullable();
            $table->string('theme', 32)->default('light');
            $table->string('language', 5)->default('pt-BR');

            // Timestamps + Soft Delete
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('email');
            $table->index('professional_type');
            $table->index('status');
            $table->index(['professional_id', 'professional_table'], 'prof_polymorphic_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_users');
    }
};
