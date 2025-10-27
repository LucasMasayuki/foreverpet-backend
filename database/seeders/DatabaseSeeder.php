<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. First create permissions
            PermissionSeeder::class,

            // 2. Then create roles and assign permissions
            RoleSeeder::class,

            // 3. Finally create professional users with roles
            ProfessionalUserSeeder::class,
        ]);
    }
}
