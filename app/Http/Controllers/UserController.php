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
        // Adiciona verificação de autenticação se necessário
        if (!auth()->check()) {
            return response()->json([], 401);
        }

        $users = User::role('Pais')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->take(5)
            ->get(['id', 'name', 'email']); // Seleciona apenas os campos necessários

        return response()->json($users);
    }
}
