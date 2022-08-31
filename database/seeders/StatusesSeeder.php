<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'name' => 'New',
        ]);

        Status::create([
            'name' => 'In Progress',
        ]);

        Status::create([
            'name' => 'Done',
        ]);

        Status::create([
            'name' => 'Paid',
        ]);

        Status::create([
            'name' => 'Cancelled',
        ]);
        Status::create([
            'name' => 'UnPaid',
        ]);
    }
}
