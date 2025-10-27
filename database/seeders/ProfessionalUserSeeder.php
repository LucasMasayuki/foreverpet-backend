<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfessionalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin User
        $superAdmin = DB::table('professional_users')->insertGetId([
            'email' => 'superadmin@foreverpet.com',
            'password' => Hash::make('senha123'),
            'name' => 'Super Administrador',
            'professional_type' => 'admin',
            'professional_id' => null,
            'professional_table' => null,
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign super-admin role
        DB::table('model_has_roles')->insert([
            'role_id' => DB::table('roles')->where('name', 'super-admin')->value('id'),
            'model_type' => 'App\\Models\\ProfessionalUser',
            'model_id' => $superAdmin,
        ]);

        // Admin User
        $admin = DB::table('professional_users')->insertGetId([
            'email' => 'admin@foreverpet.com',
            'password' => Hash::make('senha123'),
            'name' => 'Administrador',
            'professional_type' => 'admin',
            'professional_id' => null,
            'professional_table' => null,
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => DB::table('roles')->where('name', 'admin')->value('id'),
            'model_type' => 'App\\Models\\ProfessionalUser',
            'model_id' => $admin,
        ]);

        // Manager User
        $manager = DB::table('professional_users')->insertGetId([
            'email' => 'manager@foreverpet.com',
            'password' => Hash::make('senha123'),
            'name' => 'Gerente de Negócios',
            'professional_type' => 'manager',
            'professional_id' => null,
            'professional_table' => null,
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => DB::table('roles')->where('name', 'manager')->value('id'),
            'model_type' => 'App\\Models\\ProfessionalUser',
            'model_id' => $manager,
        ]);

        $this->command->info('✅ Professional users criados com sucesso!');
        $this->command->info('');
        $this->command->info('📧 Credenciais de acesso:');
        $this->command->info('  Super Admin: superadmin@foreverpet.com / senha123');
        $this->command->info('  Admin:       admin@foreverpet.com / senha123');
        $this->command->info('  Manager:     manager@foreverpet.com / senha123');
    }
}
