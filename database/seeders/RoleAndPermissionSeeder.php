<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'biens.create',
            'biens.validate',
            'biens.update.all',
            'biens.update.own',
            'biens.delete',
            'visites.request',
            'visites.validate',
            'transactions.create',
            'stats.global',
            'users.manage',
            'audit.access',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin: Total access
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Agent: Manage properties and visits
        $agentRole = Role::create(['name' => 'agent']);
        $agentRole->givePermissionTo([
            'biens.create',
            'biens.validate',
            'biens.update.all',
            'biens.delete',
            'visites.validate',
            'transactions.create',
        ]);

        // Proprietaire: Manage own properties
        $ownerRole = Role::create(['name' => 'proprietaire']);
        $ownerRole->givePermissionTo([
            'biens.create',
            'biens.update.own',
        ]);

        // Client: Request visits and view properties
        $clientRole = Role::create(['name' => 'client']);
        $clientRole->givePermissionTo([
            'visites.request',
        ]);
    }
}
