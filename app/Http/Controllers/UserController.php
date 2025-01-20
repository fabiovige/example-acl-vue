<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use App\Traits\HasPermissionCheck;

class UserController extends Controller
{
    use HasPermissionCheck;

    public function __construct()
    {
        $this->setupPermissionMiddleware('users');
    }

    public function index(Request $request)
    {
        // Se for uma requisição de busca (autocomplete)
        if ($request->has('search')) {
            $users = User::role('Pais')
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
                ->take(5)
                ->get();

            return Inertia::render('Users/Index', [
                'users' => $users,
            ]);
        }

        // Listagem normal
        $users = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Users/Index', [
            'title' => 'Usuários',
            'users' => $users,
            'can' => [
                'users_create' => auth()->user()->can('users create'),
                'users_edit' => auth()->user()->can('users edit'),
                'users_delete' => auth()->user()->can('users delete'),
            ],
            'flash' => [
                'message' => session('message'),
                'error' => session('error'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create', [
            'roles' => Role::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id']
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            if (isset($validated['roles'])) {
                $user->syncRoles($validated['roles']);
            }

            return redirect()->route('users.index')
                ->with('message', 'Usuário criado com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user->load('roles'),
            'roles' => Role::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id']
        ]);

        try {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = bcrypt($validated['password']);
            }

            $user->update($userData);

            if (isset($validated['roles'])) {
                $user->syncRoles($validated['roles']);
            } else {
                $user->syncRoles([]);
            }

            return redirect()->route('users.index')
                ->with('message', 'Usuário atualizado com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('users.index')
                ->with('message', 'Usuário excluído com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir usuário. Por favor, tente novamente.');
        }
    }

    public function search(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([], 401);
        }

        $query = User::query();

        // Se foi especificado um papel, filtra por ele
        if ($request->role) {
            $query->role($request->role);
        }

        // Se tiver termo de busca, aplica o filtro
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Limita o número de resultados (padrão: 5)
        $limit = $request->limit ?? 5;

        $users = $query->take($limit)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}
