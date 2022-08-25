<?php

namespace App\Policies;


use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view any roles');
    }

    public function view(User $user, Role $role)
    {
        if ($role->deleted_at != null) {
            return false;
        }
        return $user->can('view roles');
    }

    public function create(User $user)
    {
        return $user->can('create roles');
    }

    public function update(User $user, Role $role)
    {
        if ($role->deleted_at != null) {
            return false;
        }
        return $user->can('update roles');
    }


    public function delete(User $user, Role $role)
    {
        return $user->can('delete roles');
    } 
}