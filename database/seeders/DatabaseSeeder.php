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
                // AdminSeeder::class,
                // UserSeeder::class,
                // OmsSettingsSeeder::class,
                InvoiceStatusesSeeder::class,
                // BasicInfoSeeder::class,
                // EmailInfoSeeder::class,
                // JobsStatusesSeeder::class,
                // DepartmentSeeder::class,
                // ClientSeeder::class,
              ]
         );
    }
}
