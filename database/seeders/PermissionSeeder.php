<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for Player Notes
        $permissions = [
            'create player notes',
            'delete player notes',
            'edit player notes',
            'view player notes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign permissions to staff role
        $staffRole->syncPermissions([
            'create player notes',
            'delete player notes',
            'view player notes',
        ]);

        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        $this->command->info('✓ Permisos creados exitosamente');
        $this->command->info('✓ Roles configurados correctamente');
    }
}
