<?php

namespace App\Observers;

use App\Models\Role;
use Illuminate\Support\Facades\Artisan;

class RoleObserver
{
    public function updated(Role $role)
    {
        Artisan::call('cache:clear');
    }
}
