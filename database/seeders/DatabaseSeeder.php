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
                AdminSeeder::class,
                OmsSettingsSeeder::class,
                StatusesSeeder::class,
                DepartmentSeeder::class,
                RoleSeeder::class,  
                // UserSeeder::class,
                // InstructionSeeder::class,
                // AccountSeeder::class,
                // TransferSeeder::class,
                // TransactionSeeder::class,
                // StatementSeeder::class,
                // AttendanceSeeder::class,       
            ]
        );
    }
}
