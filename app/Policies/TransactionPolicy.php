<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
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
        if (cache()->get('current_plan') == 'Basic') {
            return false;
        }

        return $user->can('view any transactions');
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
        if (cache()->get('current_plan') == 'Basic') {
            return false;
        }

        return $user->can('create transactions');
    }
}
