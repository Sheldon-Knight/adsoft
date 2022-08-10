<?php

namespace Database\Seeders;

use App\Models\OmsSetting;
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
            'oms_name' => config('oms.settings.oms_name'),
            'oms_company_name' => config('oms.settings.oms_company_name'),
            'oms_status' => config('oms.settings.oms_status'),
            'oms_company_tel' => config('oms.settings.oms_company_tel'),
            'oms_company_address' => config('oms.settings.oms_company_address'),
            'oms_company_vat' => config('oms.settings.oms_company_vat'),
            'oms_company_registration' => config('oms.settings.oms_company_registration'),
            'oms_email' => config('oms.settings.oms_email'),
        ]);
    }
}
