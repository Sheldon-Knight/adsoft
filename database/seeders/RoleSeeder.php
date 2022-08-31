<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Accounts Permissions

        Permission::create(['name' => 'view any accounts']);
        Permission::create(['name' => 'create accounts']);
        Permission::create(['name' => 'update accounts']);
        Permission::create(['name' => 'view accounts']);
        Permission::create(['name' => 'delete accounts']);
        Permission::create(['name' => 'restore accounts']);
        Permission::create(['name' => 'force delete accounts']);
        Permission::create(['name' => 'add funds to accounts']);

        // Transfers Permissions

        Permission::create(['name' => 'view any transfers']);
        Permission::create(['name' => 'create transfers']);

        // Transactions Permissions

        Permission::create(['name' => 'view any transactions']);
        Permission::create(['name' => 'create transactions']);

        // Clients Permissions
        Permission::create(['name' => 'view any clients']);
        Permission::create(['name' => 'create clients']);
        Permission::create(['name' => 'update clients']);
        Permission::create(['name' => 'view clients']);
        Permission::create(['name' => 'delete clients']);
        Permission::create(['name' => 'restore clients']);
        Permission::create(['name' => 'force delete clients']);

        //Attendances Permissions

        Permission::create(['name' => 'view any attendances']);
        Permission::create(['name' => 'create attendances']);
        Permission::create(['name' => 'update attendances']);
        Permission::create(['name' => 'view attendances']);
        Permission::create(['name' => 'delete attendances']);
        Permission::create(['name' => 'restore attendances']);
        Permission::create(['name' => 'force delete attendances']);

        //Users Permissions

        Permission::create(['name' => 'view any users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'restore users']);
        Permission::create(['name' => 'force delete users']);
        Permission::create(['name' => 'assign users to departments']);
        Permission::create(['name' => 'remove users from departments']);

        //Roles Permissions

        Permission::create(['name' => 'view any roles']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'update roles']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'delete roles']);

        //Status Permissions

        Permission::create(['name' => 'view any statuses']);
        Permission::create(['name' => 'create statuses']);
        Permission::create(['name' => 'update statuses']);
        Permission::create(['name' => 'view statuses']);
        Permission::create(['name' => 'delete statuses']);
        Permission::create(['name' => 'restore statuses']);
        Permission::create(['name' => 'force delete statuses']);

        //Status Permissions

        Permission::create(['name' => 'view any departments']);
        Permission::create(['name' => 'create departments']);
        Permission::create(['name' => 'update departments']);
        Permission::create(['name' => 'view departments']);
        Permission::create(['name' => 'delete departments']);
        Permission::create(['name' => 'restore departments']);
        Permission::create(['name' => 'force delete departments']);

        //Quotes Permissions

        Permission::create(['name' => 'view any quotes']);
        Permission::create(['name' => 'create quotes']);
        Permission::create(['name' => 'view quotes']);
        Permission::create(['name' => 'delete quotes']);
        Permission::create(['name' => 'restore quotes']);
        Permission::create(['name' => 'update quotes']);
        Permission::create(['name' => 'force delete quotes']);
        Permission::create(['name' => 'convert quotes to invoices']);
        Permission::create(['name' => 'email quotes']);
        Permission::create(['name' => 'download pdf quotes']);

        //Invoices Permissions

        Permission::create(['name' => 'view any invoices']);
        Permission::create(['name' => 'create invoices']);
        Permission::create(['name' => 'update invoices']);
        Permission::create(['name' => 'view invoices']);
        Permission::create(['name' => 'delete invoices']);
        Permission::create(['name' => 'restore invoices']);
        Permission::create(['name' => 'force delete invoices']);
        Permission::create(['name' => 'email invoices']);
        Permission::create(['name' => 'download pdf invoices']);

        //Jobs Permissions

        Permission::create(['name' => 'view any jobs']);
        Permission::create(['name' => 'create jobs']);
        Permission::create(['name' => 'update jobs']);
        Permission::create(['name' => 'view jobs']);
        Permission::create(['name' => 'delete jobs']);
        Permission::create(['name' => 'restore jobs']);
        Permission::create(['name' => 'force delete jobs']);

        //Instructions Permissions

        Permission::create(['name' => 'view any instructions']);
        Permission::create(['name' => 'create instructions']);
        Permission::create(['name' => 'update instructions']);
        Permission::create(['name' => 'view instructions']);
        Permission::create(['name' => 'delete instructions']);
        Permission::create(['name' => 'restore instructions']);
        Permission::create(['name' => 'force delete instructions']);

        //Oms Settings Permissions

        Permission::create(['name' => 'change application settings']);

        // Admin Role with All Permissions

        $role = Role::create([
            'name' => 'Super Admin',
        ]);

        // give first user all permissions
        $user = User::find(1);

        $user->assignRole('Super Admin');

        $permissions = [];

        foreach (Permission::all() as $permission) {
            $permissions[] = $permission->name;
        }

        $role->givePermissionTo([$permissions]);
    }
}
