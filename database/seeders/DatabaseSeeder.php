<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Department;
use App\Models\Instruction;
use App\Models\Invoice;
use App\Models\OmsSetting;
use App\Models\Status;
use App\Models\User;
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
                UserSeeder::class,
                OmsSettingsSeeder::class,
                StatusesSeeder::class,
                DepartmentSeeder::class,
                InstructionSeeder::class,
                AccountSeeder::class,
            ]
        );
    }
}
