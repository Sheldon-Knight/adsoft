<?php

namespace App\Policies;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        };
        if (cache()->get('current_plan') == "Basic") {
            return false;
        }
        return $user->can('view any transfers');
    }

    public function create(User $user)
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        };
        if (cache()->get('current_plan') == "Basic") {
            return false;
        }
        return $user->can('create transfers');
    }
}
