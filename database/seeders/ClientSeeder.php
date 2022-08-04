<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    private $faker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::factory()->times(12)->create();
    }
}
