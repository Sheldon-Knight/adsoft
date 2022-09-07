<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{   
    public function run()
    {
        User::factory()->times(2)->create()
        ->each(function ($user) {
            $user->assignRole('Super Admin');            
        });
    }    
}
