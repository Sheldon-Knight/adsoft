<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('view any departments');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Department $department)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($department->deleted_at != null) {
            return false;
        }

        return $user->can('view departments');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('create departments');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Department $department)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($department->deleted_at != null) {
            return false;
        }

        return $user->can('update departments');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Department $department)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($department->deleted_at != null) {
            return false;
        }

        return $user->can('delete departments');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Department $department)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($department->deleted_at === null) {
            return false;
        }

        return $user->can('restore departments');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Department $department)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($department->deleted_at === null) {
            return false;
        }

        return $user->can('force delete departments');
    }
}
