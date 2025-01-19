<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\HasPermissionCheck;

class RoleController extends Controller
{
    use HasPermissionCheck;

    public function __construct()
    {
        $this->setupPermissionMiddleware('roles');
    }

    public function index()
    {
        $roles = Role::with('permissions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Roles/Index', [
            'title' => 'Roles',
            'roles' => $roles,
            'can' => [
                'roles_create' => auth()->user()->can('roles create'),
                'roles_edit' => auth()->user()->can('roles edit'),
                'roles_delete' => auth()->user()->can('roles delete'),
            ],
            'flash' => [
                'message' => session('message'),
                'error' => session('error'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Roles/Create', [
            'permissions' => Permission::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles'
            ],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        try {
            $role = Role::create([
                'name' => $validated['name']
            ]);

            if (isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            return redirect()->route('roles.index')
                ->with('message', 'Função criada com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['name' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function edit(Role $role)
    {
        return Inertia::render('Roles/Edit', [
            'role' => $role->load('permissions'),
            'permissions' => Permission::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name,' . $role->id
            ],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        try {
            $role->update([
                'name' => $validated['name']
            ]);

            if (isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            } else {
                $role->syncPermissions([]);
            }

            return redirect()->route('roles.index')
                ->with('message', 'Função atualizada com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['name' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return redirect()->route('roles.index')
                ->with('message', 'Função excluída com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir função. Por favor, tente novamente.');
        }
    }
}
