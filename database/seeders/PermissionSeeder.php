<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'professional';

        // Define all permissions grouped by module
        $permissions = [
            // Users Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.export',

            // Pets Management
            'pets.view',
            'pets.create',
            'pets.edit',
            'pets.delete',
            'pets.export',

            // Vets Management
            'vets.view',
            'vets.create',
            'vets.edit',
            'vets.delete',
            'vets.approve',

            // ONGs Management
            'ongs.view',
            'ongs.create',
            'ongs.edit',
            'ongs.delete',
            'ongs.approve',

            // Agenda Management
            'agenda.view',
            'agenda.create',
            'agenda.edit',
            'agenda.delete',

            // Places Management
            'places.view',
            'places.create',
            'places.edit',
            'places.delete',
            'places.approve',

            // Campaigns Management
            'campaigns.view',
            'campaigns.create',
            'campaigns.edit',
            'campaigns.delete',
            'campaigns.send',

            // Store Management
            'store.view',
            'store.create',
            'store.edit',
            'store.delete',
            'store.orders',

            // Reports
            'reports.view',
            'reports.export',
            'reports.financial',
            'reports.analytics',

            // Settings
            'settings.view',
            'settings.edit',
            'settings.system',

            // Professional Users Management
            'professional_users.view',
            'professional_users.create',
            'professional_users.edit',
            'professional_users.delete',
            'professional_users.approve',

            // Roles & Permissions Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.assign',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        $this->command->info('✅ Permissions criadas com sucesso!');
    }
}
