<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->times(1)->create()
            ->each(function ($user) {
                $user->assignRole('Employee');
            });
    }
}
