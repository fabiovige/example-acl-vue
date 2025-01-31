<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use App\Traits\HasPermissionCheck;

class PermissionController extends Controller
{
    use HasPermissionCheck;

    public function __construct()
    {
        $this->setupPermissionMiddleware('permissions');
    }

    public function index()
    {
        $query = Permission::query()
            ->when(request()->input('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name');

        $permissions = $query->paginate(15)
            ->withQueryString()
            ->through(fn($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'created_at' => $permission->created_at,
            ]);

        return Inertia::render('Permissions/Index', [
            'permissions' => $permissions,
            'filters' => request()->only(['search']),
            'can' => [
                'create' => auth()->user()->can('permissions create'),
                'edit' => auth()->user()->can('permissions edit'),
                'delete' => auth()->user()->can('permissions delete'),
            ]
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
