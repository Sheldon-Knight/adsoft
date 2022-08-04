<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\InvoiceBasicInfo;
use App\Models\InvoiceStatus;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Department::create([
            'name' => "Reception",
        ]);

        Department::create([
            'name' => "Development",
        ]);

        Department::create([
            'name' => "Production",
        ]);
    }
}
