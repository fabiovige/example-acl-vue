<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Permissions/Index', [
            'title' => 'Permissions',
            'permissions' => $permissions,
            'flash' => [
                'message' => session('message'),
                'error' => session('error'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Permissions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:permissions'
            ],
        ]);

        try {
            Permission::create($validated);

            return redirect()->route('permissions.index')
                ->with('message', 'Permissão criada com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['name' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function edit(Permission $permission)
    {
        return Inertia::render('Permissions/Edit', [
            'permission' => $permission
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:permissions,name,' . $permission->id
            ],
        ]);

        try {
            $permission->update($validated);

            return redirect()->route('permissions.index')
                ->with('message', 'Permissão atualizada com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['name' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();

            return redirect()->route('permissions.index')
                ->with('message', 'Permissão excluída com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir permissão. Por favor, tente novamente.');
        }
    }
}
