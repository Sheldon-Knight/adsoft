<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

        return auth()->user()->can('view any users');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($model->deleted_at != null) {
            return false;
        }

        return auth()->user()->can('view users');
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

        return auth()->user()->can('create users');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($model->deleted_at != null) {
            return false;
        }

        return auth()->user()->can('update users');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {

        if ($model->is_admin) {
            return false;
        }


        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($model->deleted_at != null) {
            return false;
        }

        return auth()->user()->can('delete users');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {

        if ($model->is_admin) {
            return false;
        }

      
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($model->deleted_at === null) {
            return false;
        }

        return auth()->user()->can('restore users', $user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {

        if ($model->is_admin) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($model->deleted_at === null) {
            return false;
        }

        return auth()->user()->can('force delete users');
    }
}
