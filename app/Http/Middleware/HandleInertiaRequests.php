<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'can' => [
                        'users_view' => $request->user()->can('users view'),
                        'users_create' => $request->user()->can('users create'),
                        'users_edit' => $request->user()->can('users edit'),
                        'users_delete' => $request->user()->can('users delete'),
                        'users_view_all' => $request->user()->can('users view all'),

                        'roles_view' => $request->user()->can('roles view'),
                        'roles_create' => $request->user()->can('roles create'),
                        'roles_edit' => $request->user()->can('roles edit'),
                        'roles_delete' => $request->user()->can('roles delete'),

                        'permissions_view' => $request->user()->can('permissions view'),
                        'permissions_create' => $request->user()->can('permissions create'),
                        'permissions_edit' => $request->user()->can('permissions edit'),
                        'permissions_delete' => $request->user()->can('permissions delete'),

                        'children_view' => $request->user()->can('children view'),
                        'children_create' => $request->user()->can('children create'),
                        'children_edit' => $request->user()->can('children edit'),
                        'children_delete' => $request->user()->can('children delete'),
                        'children_view_all' => $request->user()->can('children view all'),
                    ],
                ] : null,
            ],
        ];
    }
}
