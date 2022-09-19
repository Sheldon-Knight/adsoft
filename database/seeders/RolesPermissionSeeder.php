<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        $roles = [
            ['name' => 'Super Admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Client', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Employee', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        $permissions = [
            ['name' => 'view any accounts', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create accounts', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update accounts', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view accounts', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete accounts', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore accounts', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete accounts', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'add funds to accounts', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any transfers', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create transfers', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any transactions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create transactions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any clients', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create clients', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update clients', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view clients', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete clients', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore clients', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete clients', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any attendances', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create attendances', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update attendances', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view attendances', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete attendances', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore attendances', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete attendances', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete users', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'assign users to departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'remove users from departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete roles', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any statuses', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create statuses', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update statuses', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view statuses', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete statuses', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore statuses', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete statuses', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete departments', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'convert quotes to invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'email quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'download pdf quotes', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'email invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'download pdf invoices', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any jobs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create jobs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update jobs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view jobs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete jobs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore jobs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete jobs', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any instructions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create instructions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update instructions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view instructions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete instructions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore instructions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete instructions', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'change application settings', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any leaves', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update leaves', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view leaves', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete leaves', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore leaves', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete leaves', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view any inventorys', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'create inventorys', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'update inventorys', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'view inventorys', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'delete inventorys', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'restore inventorys', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'force delete inventorys', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];

        Role::insert($roles);

        Permission::insert($permissions);

        $superAdminRole = Role::where('name', 'Super Admin')->first();

        $givePermissionTo = [];

        foreach (Permission::all() as $permission) {
            $givePermissionTo[] = ['permission_id' => $permission->id, 'role_id' => $superAdminRole->id];
        }

        DB::table('role_has_permissions')->insert($givePermissionTo);
    }
}
