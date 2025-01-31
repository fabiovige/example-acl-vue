<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\User;
use App\Http\Requests\ChildRequest;
use Inertia\Inertia;
use App\Traits\HasPermissionCheck;

class ChildController extends Controller
{
    use HasPermissionCheck;

    public function __construct()
    {
        $this->setupPermissionMiddleware('children');
    }

    public function index()
    {
        $user = auth()->user();

        // Usuário só vê as crianças que tem permissão
        $query = Child::query();

        // Se não pode ver todas as crianças, filtra apenas as próprias
        if (!$user->can('children view all')) {
            $query->where('parent_id', $user->id);
        }

        $children = $query->with(['parent' => function($query) {
                $query->select('id', 'name', 'email');
            }])
            ->select([
                'id',
                'name',
                'birth_date',
                'gender',
                'parent_id',
                'father_name',
                'mother_name',
                'created_at'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        dd($children);
        // Debug para verificar a query
        \Log::info('Children Query', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'results' => $children->items()
        ]);

        return Inertia::render('Children/Index', [
            'title' => 'Crianças',
            'children' => $children,
            'can' => [
                'children_create' => $user->can('children create'),
                'children_edit' => $user->can('children edit'),
                'children_delete' => $user->can('children delete'),
                'children_view_all' => $user->can('children view all'),
            ],
            'flash' => [
                'message' => session('message'),
                'error' => session('error'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Children/Create');
    }

    public function store(ChildRequest $request)
    {
        try {
            $validated = $request->validated();

            // Se não pode ver todas as crianças, força parent_id
            if (!auth()->user()->can('children view all')) {
                $validated['parent_id'] = auth()->id();
            }

            Child::create($validated);

            return redirect()
                ->route('children.index')
                ->with('message', 'Criança cadastrada com sucesso.');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Ocorreu um erro interno. Por favor, tente novamente.'])
                ->withInput();
        }
    }

    public function edit(Child $child)
    {
        // Verifica se usuário pode editar esta criança específica
        if (!auth()->user()->can('children view all') && $child->parent_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Children/Edit', [
            'child' => $child
        ]);
    }

    public function update(ChildRequest $request, Child $child)
    {
        // Verifica se usuário pode editar esta criança específica
        if (!auth()->user()->can('children view all') && $child->parent_id !== auth()->id()) {
            abort(403);
        }

        try {
            $child->update($request->validated());
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
        // Verifica se usuário pode excluir esta criança específica
        if (!auth()->user()->can('children view all') && $child->parent_id !== auth()->id()) {
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
