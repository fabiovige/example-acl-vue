<?php

namespace App\Http\Controllers;

use App\Models\Child;
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
        $query = Child::query()
            ->with('parent')
            ->when(!auth()->user()->can('children view all'), function ($query) {
                $query->where('parent_id', auth()->id());
            });

        $children = $query->latest()->paginate(10);

        return Inertia::render('Children/Index', [
            'children' => $children,
            'can' => [
                'create' => auth()->user()->can('children create'),
                'edit' => auth()->user()->can('children edit'),
                'delete' => auth()->user()->can('children delete'),
                'view_all' => auth()->user()->can('children view all'),
            ],
            'filters' => request()->all(['search', 'role', 'trashed']),
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

            if (!auth()->user()->can('children view all')) {
                $validated['parent_id'] = auth()->id();
            }

            Child::create($validated);

            return redirect()->route('children.index')
                ->with('message', 'Criança cadastrada com sucesso.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao cadastrar criança.'])->withInput();
        }
    }

    public function edit(Child $child)
    {
        $this->authorize('update', $child);

        return Inertia::render('Children/Edit', [
            'child' => $child
        ]);
    }

    public function update(ChildRequest $request, Child $child)
    {
        $this->authorize('update', $child);

        try {
            $child->update($request->validated());

            return redirect()->route('children.index')
                ->with('message', 'Criança atualizada com sucesso.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao atualizar criança.'])->withInput();
        }
    }

    public function destroy(Child $child)
    {
        $this->authorize('delete', $child);

        try {
            $child->delete();
            return redirect()->route('children.index')
                ->with('message', 'Criança excluída com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir criança.');
        }
    }
}
