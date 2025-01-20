<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\HasPermissionCheck;

class ChildController extends Controller
{
    use HasPermissionCheck;

    private array $rules = [
        'name' => ['required', 'string', 'max:100'],
        'birth_date' => ['required', 'date'],
        'ethnicity' => ['required', 'in:branco,pardo,negro,indigena,amarelo'],
        'gender' => ['required', 'in:masculino,feminino'],
        'address' => ['required', 'string', 'max:255'],
        'address_number' => ['required', 'string', 'max:10'],
        'complement' => ['nullable', 'string', 'max:100'],
        'neighborhood' => ['required', 'string', 'max:100'],
        'city' => ['required', 'string', 'max:100'],
        'state' => ['required', 'string', 'max:2'],
        'zipcode' => ['required', 'string', 'max:9'],
        'father_name' => ['nullable', 'string', 'max:100'],
        'father_phone' => ['nullable', 'string', 'max:15'],
        'mother_name' => ['nullable', 'string', 'max:100'],
        'mother_phone' => ['nullable', 'string', 'max:15'],
        'user_id' => ['required', 'exists:users,id'],
    ];

    // Mensagens personalizadas
    private array $messages = [
        'user_id.required' => 'O usuário responsável é obrigatório.',
        'user_id.exists' => 'O usuário responsável selecionado é inválido.'
    ];

    public function __construct()
    {
        $this->setupPermissionMiddleware('children');
    }

    public function index()
    {
        $user = auth()->user();

        // Se for pai, vê apenas suas crianças
        // Se for admin ou super admin, vê todas
        $query = $user->hasRole('Pais')
            ? Child::where('user_id', $user->id)
            : Child::query();

        $children = $query->with('parent')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Children/Index', [
            'title' => 'Crianças',
            'children' => $children,
            'can' => [
                'children_create' => $user->can('children create'),
                'children_edit' => $user->can('children edit'),
                'children_delete' => $user->can('children delete'),
            ],
            'flash' => [
                'message' => session('message'),
                'error' => session('error'),
            ],
        ]);
    }

    public function create()
    {
        // Se for pai, só pode cadastrar crianças para si mesmo
        if (auth()->user()->hasRole('Pais')) {
            return Inertia::render('Children/Create', [
                'parent_id' => auth()->id()
            ]);
        }

        // Se for admin ou super admin, pode escolher o pai
        return Inertia::render('Children/Create', [
            'parents' => User::role('Pais')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules, $this->messages);

        try {
            Child::create($validated);

            return redirect()->route('children.index')
                ->with('message', 'Criança cadastrada com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function edit(Child $child)
    {
        // Verifica se o usuário pode editar esta criança específica
        if (auth()->user()->hasRole('Pais') && $child->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Children/Edit', [
            'child' => $child,
            'parents' => User::role('Pais')->get()
        ]);
    }

    public function update(Request $request, Child $child)
    {
        // Verifica se o usuário pode atualizar esta criança específica
        if (auth()->user()->hasRole('Pais') && $child->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate($this->rules);

        try {
            $child->update($validated);

            return redirect()->route('children.index')
                ->with('message', 'Criança atualizada com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function destroy(Child $child)
    {
        // Verifica se o usuário pode excluir esta criança específica
        if (auth()->user()->hasRole('Pais') && $child->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $child->delete();

            return redirect()->route('children.index')
                ->with('message', 'Criança excluída com sucesso.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir criança. Por favor, tente novamente.');
        }
    }
}
