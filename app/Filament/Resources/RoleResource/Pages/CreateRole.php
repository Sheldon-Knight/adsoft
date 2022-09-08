<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Permission;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $role = static::getModel()::create($data);

        $users = User::whereIn('id', $data['users'])->get();

        $permissions = Permission::whereIn('id', $data['permissions'])->get();

        if ($permissions) {
            $givePermissionTo = [];

            foreach ($permissions  as $permission) {
                $givePermissionTo[] = ['permission_id' => $permission->id, 'role_id' => $role->id];
            }

            DB::table('role_has_permissions')->insert($givePermissionTo);
        }

        if ($users) {
            foreach ($users  as $user) {
                $user->assignRole($role->name);
            }
        }

        return $role;
    }
}
