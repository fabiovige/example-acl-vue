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
        $query = $user->hasRole('Pais')
            ? Child::where('parent_id', $user->id)
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
        if (auth()->user()->hasRole('Pais')) {
            return Inertia::render('Children/Create', [
                'parent_id' => auth()->id(),
                'employees' => User::role('Funcionario')->get()
            ]);
        }

        return Inertia::render('Children/Create', [
            'parents' => User::role('Pais')->get(),
            'employees' => User::role('Funcionario')->get()
        ]);
    }

    public function store(ChildRequest $request)
    {
        try {
            $validated = $request->validated();

            if (auth()->user()->hasRole('Pais')) {
                $validated['parent_id'] = auth()->id();
            }

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
        if (auth()->user()->hasRole('Pais') && $child->parent_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Children/Edit', [
            'child' => $child,
            'parents' => User::role('Pais')->get(),
            'employees' => User::role('Funcionario')->get()
        ]);
    }

    public function update(ChildRequest $request, Child $child)
    {
        if (auth()->user()->hasRole('Pais') && $child->parent_id !== auth()->id()) {
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
        if (auth()->user()->hasRole('Pais') && $child->parent_id !== auth()->id()) {
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
