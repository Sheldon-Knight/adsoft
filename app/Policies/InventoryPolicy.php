<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
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

        return $user->can('view any inventorys');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Inventory $inventory)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($inventory->deleted_at != null) {
            return false;
        }

        return $user->can('view inventorys');
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

        return $user->can('create inventorys');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Inventory $inventory)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($inventory->deleted_at != null) {
            return false;
        }

        return $user->can('update inventorys');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Inventory $inventory)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($inventory->deleted_at != null) {
            return false;
        }

        return $user->can('delete inventorys');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Inventory $inventory)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($inventory->deleted_at === null) {
            return false;
        }

        return $user->can('restore inventorys');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Inventory $inventory)
    {
        if ($user->is_admin == true) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }
        if ($inventory->deleted_at === null) {
            return false;
        }

        return $user->can('force delete inventorys');
    }
}
