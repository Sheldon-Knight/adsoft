<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                OmsSettingsSeeder::class,
                FeatureSeeder::class,
                RolesPermissionSeeder::class,
                // AdminSeeder::class,
                // EmployeeSeeder::class,
                // ClientSeeder::class,
                StatusesSeeder::class,
                DepartmentSeeder::class,
                // InstructionSeeder::class,
                // AccountSeeder::class,
                // TransferSeeder::class,
                // TransactionSeeder::class,
                // StatementSeeder::class,
                // AttendanceSeeder::class,
                // LeaveSeeder::class,
            ]
        );

        app()['cache']->forget('spatie.permission.cache');
    }
}
