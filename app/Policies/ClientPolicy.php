<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
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
        };
        return $user->can('view any clients');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Client $client)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        };

        if ($client->deleted_at != null) {
            return false;
        }

        return $user->can('view clients');
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
        };
        return $user->can('create clients');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Client $client)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        };

        if ($client->deleted_at != null) {
            return false;
        }

        return $user->can('update clients');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Client $client)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        };

        if ($client->deleted_at != null) {
            return false;
        }

        return $user->can('delete clients');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Client $client)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        };
        if ($client->deleted_at === null) {
            return false;
        }

        return $user->can('restore clients');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Client $client)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        };
        if ($client->deleted_at === null) {
            return false;
        }
        return $user->can('force delete clients');
    }
}
