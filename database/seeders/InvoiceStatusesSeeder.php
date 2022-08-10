<?php

namespace Database\Seeders;

use App\Models\InvoiceBasicInfo;
use App\Models\InvoiceStatus;
use Illuminate\Database\Seeder;

class InvoiceStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InvoiceStatus::create([
            'name' => "Unpaid",
        ]);

        InvoiceStatus::create([
            'name' => "Paid",
        ]);

        InvoiceStatus::create([
            'name' => "Pending",
        ]);

        InvoiceStatus::create([
            'name' => "Pending",
            'is_quote' => true,
        ]);
        
        InvoiceStatus::create([
            'name' => "Paid",
            'is_quote' => true,
        ]);

        InvoiceStatus::create([
            'name' => "Unpaid",
            'is_quote' => true,
        ]);

    }
}
