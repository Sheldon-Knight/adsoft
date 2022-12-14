<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
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
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('view any statuses');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Status $status)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($status->deleted_at != null) {
            return false;
        }

        return $user->can('view statuses');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('create statuses');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Status $status)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($status->deleted_at != null) {
            return false;
        }

        return $user->can('update statuses');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Status $status)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($status->deleted_at != null) {
            return false;
        }

        return $user->can('delete statuses');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Status $status)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($status->deleted_at === null) {
            return false;
        }

        return $user->can('restore statuses');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Status $status)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($status->deleted_at === null) {
            return false;
        }

        return $user->can('force delete statuses');
    }
}
