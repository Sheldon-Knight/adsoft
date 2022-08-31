<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('view any roles');
    }

    public function view(User $user, Role $role)
    {
        if ($role->name == "Super Admin") {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($role->deleted_at != null) {
            return false;
        }

        return $user->can('view roles');
    }

    public function create(User $user)
    {
        
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('create roles');
    }

    public function update(User $user, Role $role)
    {
        if ($role->name == "Super Admin") {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($role->deleted_at != null) {
            return false;
        }

        return $user->can('update roles');
    }

    public function delete(User $user, Role $role)
    {

        if ($role->name == "Super Admin") {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('delete roles');
    }
}
