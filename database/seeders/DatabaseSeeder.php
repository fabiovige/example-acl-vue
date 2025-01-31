<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Child;

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
            'users' => ['view', 'create', 'edit', 'delete', 'view all'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'permissions' => ['view', 'create', 'edit', 'delete'],
            'children' => ['view', 'create', 'edit', 'delete', 'view all'],
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
        $roleEditor = Role::create(['name' => 'Editor']);
        $roleRevisor = Role::create(['name' => 'Revisor']);
        $roleGerente = Role::create(['name' => 'Gerente']);

        // Permissões do Admin (acesso total)
        $roleAdmin->givePermissionTo($permissions);

        // Permissões do Editor
        $roleEditor->givePermissionTo([
            'children view',
            'children create',
            'children edit',
            'children view all',
            'users view'
        ]);

        // Permissões do Revisor
        $roleRevisor->givePermissionTo([
            'children view',
            'children edit',
            'children view all',
            'users view'
        ]);

        // Permissões do Gerente
        $roleGerente->givePermissionTo([
            'children view',
            'children create',
            'children edit',
            'children delete',
            'children view all',
            'users view',
            'users view all',
            'users create',
            'users edit'
        ]);

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

        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
        ]);

        $revisor = User::create([
            'name' => 'Revisor',
            'email' => 'revisor@example.com',
            'password' => bcrypt('password'),
        ]);

        $gerente = User::create([
            'name' => 'Gerente',
            'email' => 'gerente@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign Roles to Users
        $superAdmin->assignRole('Super Admin');
        $admin->assignRole('Admin');
        $editor->assignRole('Editor');
        $revisor->assignRole('Revisor');
        $gerente->assignRole('Gerente');

        // Criar criança exemplo vinculada ao Editor
        Child::create([
            'name' => 'Criança Exemplo',
            'birth_date' => now()->subYears(5),
            'ethnicity' => 'branco',
            'gender' => 'masculino',
            'address' => 'Rua Exemplo',
            'address_number' => '123',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zipcode' => '01001-000',
            'father_name' => 'Editor Exemplo',
            'father_phone' => '(11) 99999-9999',
            'parent_id' => $editor->id
        ]);
    }
}
