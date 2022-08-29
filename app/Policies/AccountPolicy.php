<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\OmsSetting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccountPolicy
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
     
        if (cache()->get('banking_feature') == false) {
           return false;
        }

        return $user->can('view any accounts');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Account $account)
    {


        if ($account->deleted_at != null) {
            return false;
        }

        return $user->can('view accounts');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create accounts');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Account $account)
    {

        if ($account->deleted_at != null) {
            return false;
        }

        return $user->can('update accounts');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Account $account)
    {

        if ($account->deleted_at != null) {
            return false;
        }

        return $user->can('delete accounts');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Account $account)
    {

        if ($account->deleted_at === null) {
            return false;
        }

        return $user->can('restore accounts');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Account $account)
    {
        if ($account->deleted_at === null) {
            return false;
        }

        return $user->can('force delete accounts');
    }
}
