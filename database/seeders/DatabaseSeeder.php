<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Grupos de permissões por módulo
        $permissionGroups = [
            'users' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'permissions' => ['view', 'create', 'edit', 'delete'],
            // Novos módulos podem ser adicionados aqui
            // 'products' => ['view', 'create', 'edit', 'delete'],
            // 'orders' => ['view', 'create', 'edit', 'delete'],
        ];

        $permissions = [];
        foreach ($permissionGroups as $group => $actions) {
            foreach ($actions as $action) {
                $permissions[] = $group . ' ' . $action;
            }
        }

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles
        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleAdmin = Role::create(['name' => 'Admin']);

        // Give all permissions to Admin role
        $roleAdmin->givePermissionTo($permissions);

        // Create Users
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign Roles to Users
        $superAdmin->assignRole('Super Admin');
        $admin->assignRole('Admin');
    }
}
