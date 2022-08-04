<?php

namespace Database\Seeders;


use App\Models\InvoiceEmailInfo;
use Illuminate\Database\Seeder;

class EmailInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         
        InvoiceEmailInfo::create([
           'message' => '<p>Hi [CLIENT_NAME]</p><p>This is A Template You Can Edit IT Here!</p><p>Thank You</p>',
        ]);  
        
        InvoiceEmailInfo::create([
           'message' => '<p>Hi [CLIENT_NAME]</p><p>This is A Template You Can Edit IT Here!</p><p>Thank You</p>',
        ]);   
    }
}
