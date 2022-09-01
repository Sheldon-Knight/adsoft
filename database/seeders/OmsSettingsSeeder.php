<?php

namespace Database\Seeders;

use App\Models\OmsSetting;
use File;
use Illuminate\Database\Seeder;

class OmsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OmsSetting::create([
            'oms_name' => 'Your Office Managment Name',
            'oms_company_name' => 'Company Name',
            'oms_company_tel' => '012-345-6789',
            'oms_company_address' => 'Your Address',
            'oms_company_vat' => '123456789',
            'oms_company_registration' => '987654321',
            'oms_email' => 'youremail@example.com',
            'oms_logo' => 'Site-Logo.png',
            'quote_notes' => 'This Is A Note For The Quote',
            'invoice_notes' => 'This Is A Note For The Invoice',
        ]);

        File::copy(public_path('demo-logo.png'), public_path('storage/Site-Logo.png'));
        File::copy(public_path('demo-logo.png'), public_path('storage/demo-logo.png'));

        cache()->forever('oms_name', OmsSetting::first()->oms_name);
    }
}
