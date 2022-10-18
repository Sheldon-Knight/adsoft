<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // User::factory()->times(2)->create(['is_admin' => false])
        //     ->each(function ($user) {
        //         $user->assignRole('Super Admin');
        //     });

        $user = User::create([
            'name' => "Aj",
            'surname' => "Joubert",
            'phone' => "0843682219",
            'address' => "21 Faker Street",
            'email' => "demo@demo.com",
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => str()->random(10),
            'gender' => 'male',
        ]);

        $user->assignRole('Super Admin');
    }
}
