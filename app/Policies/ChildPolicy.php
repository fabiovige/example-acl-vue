<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\User;

class ChildPolicy
{
    public function viewAny(User $user)
    {
        return $user->can('children view');
    }

    public function view(User $user, Child $child)
    {
        return $user->can('children view all') || $child->parent_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->can('children create');
    }

    public function update(User $user, Child $child)
    {
        return $user->can('children edit') &&
               ($user->can('children view all') || $child->parent_id === $user->id);
    }

    public function delete(User $user, Child $child)
    {
        return $user->can('children delete') &&
               ($user->can('children view all') || $child->parent_id === $user->id);
    }
}
