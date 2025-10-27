<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guardName = 'professional';

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => $guardName]);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guardName]);
        $vet = Role::firstOrCreate(['name' => 'vet', 'guard_name' => $guardName]);
        $vetManager = Role::firstOrCreate(['name' => 'vet-manager', 'guard_name' => $guardName]);
        $vetStaff = Role::firstOrCreate(['name' => 'vet-staff', 'guard_name' => $guardName]);
        $ongAdmin = Role::firstOrCreate(['name' => 'ong-admin', 'guard_name' => $guardName]);
        $ongMember = Role::firstOrCreate(['name' => 'ong-member', 'guard_name' => $guardName]);
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => $guardName]);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => $guardName]);

        // Super Admin - ALL permissions
        $superAdmin->givePermissionTo(Permission::where('guard_name', $guardName)->get());

        // Admin - Almost all permissions (except system settings)
        $admin->givePermissionTo([
            // Users
            'users.view', 'users.create', 'users.edit', 'users.delete', 'users.export',

            // Pets
            'pets.view', 'pets.edit', 'pets.export',

            // Vets
            'vets.view', 'vets.create', 'vets.edit', 'vets.delete', 'vets.approve',

            // ONGs
            'ongs.view', 'ongs.create', 'ongs.edit', 'ongs.delete', 'ongs.approve',

            // Places
            'places.view', 'places.create', 'places.edit', 'places.delete', 'places.approve',

            // Campaigns
            'campaigns.view', 'campaigns.create', 'campaigns.edit', 'campaigns.delete', 'campaigns.send',

            // Reports
            'reports.view', 'reports.export', 'reports.financial', 'reports.analytics',

            // Professional Users
            'professional_users.view', 'professional_users.create', 'professional_users.edit', 'professional_users.approve',

            // Settings
            'settings.view', 'settings.edit',
        ]);

        // Vet Manager - Full vet clinic management
        $vetManager->givePermissionTo([
            'users.view',
            'pets.view', 'pets.create', 'pets.edit', 'pets.delete',
            'agenda.view', 'agenda.create', 'agenda.edit', 'agenda.delete',
            'vets.view', 'vets.edit',
            'reports.view', 'reports.export',
            'settings.view',
        ]);

        // Vet - Basic vet permissions
        $vet->givePermissionTo([
            'users.view',
            'pets.view', 'pets.create', 'pets.edit',
            'agenda.view', 'agenda.create', 'agenda.edit',
            'vets.view',
        ]);

        // Vet Staff - Limited permissions (reception, etc)
        $vetStaff->givePermissionTo([
            'users.view',
            'pets.view',
            'agenda.view', 'agenda.create', 'agenda.edit',
        ]);

        // ONG Admin - Full ONG management
        $ongAdmin->givePermissionTo([
            'pets.view', 'pets.create', 'pets.edit', 'pets.delete',
            'ongs.view', 'ongs.edit',
            'places.view',
            'reports.view', 'reports.export',
            'settings.view',
        ]);

        // ONG Member - Basic ONG permissions
        $ongMember->givePermissionTo([
            'pets.view', 'pets.create', 'pets.edit',
            'ongs.view',
        ]);

        // Staff - General staff permissions
        $staff->givePermissionTo([
            'users.view',
            'pets.view',
            'places.view',
            'agenda.view',
        ]);

        // Manager - Business manager permissions
        $manager->givePermissionTo([
            'users.view', 'users.export',
            'pets.view', 'pets.export',
            'vets.view',
            'ongs.view',
            'places.view',
            'campaigns.view',
            'store.view', 'store.orders',
            'reports.view', 'reports.export', 'reports.financial', 'reports.analytics',
            'settings.view',
        ]);

        $this->command->info('✅ Roles criadas e permissions atribuídas com sucesso!');
    }
}
