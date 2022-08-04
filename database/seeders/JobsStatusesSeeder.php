<?php

namespace Database\Seeders;

use App\Models\InvoiceBasicInfo;
use App\Models\InvoiceStatus;
use App\Models\Status;
use Illuminate\Database\Seeder;

class JobsStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Status::create([
            'name' => "New",
        ]);

        Status::create([
            'name' => "In Progress",
        ]);

        Status::create([
            'name' => "Done",
        ]);
    }
}
