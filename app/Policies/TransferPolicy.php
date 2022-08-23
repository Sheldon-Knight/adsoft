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
        return $user->can('view any transfers');
    }  
     
    public function create(User $user)
    {
        return $user->can('create transfers');
    }   
}
