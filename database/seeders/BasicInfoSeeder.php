<?php

namespace Database\Seeders;

use App\Models\InvoiceBasicInfo;
use App\Models\InvoiceStatus;
use Illuminate\Database\Seeder;

class BasicInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $invoiceBasicInfo = InvoiceBasicInfo::create([
            'oms_company_name' => config('oms.settings.oms_company_name'),
            'oms_company_tel' => config('oms.settings.oms_company_tel'),
            'oms_company_address' => config('oms.settings.oms_company_address'),
            'oms_company_vat' => config('oms.settings.oms_company_vat'),
            'oms_company_registration' => config('oms.settings.oms_company_registration'),
            'series' => "INV",
            'invoice_notes' => config('oms.settings.invoice_notes'),
        ]);

        $invoiceBasicInfo->addMedia(public_path('images/DemoLogo.png'))->preservingOriginal()->toMediaCollection("invoice_logo");
        $invoiceBasicInfo->invoice_logo = $invoiceBasicInfo->getFirstMediaUrl('invoice_logo');
        $invoiceBasicInfo->save();


        $status = InvoiceStatus::where('is_quote', false)->first();
        $invoiceBasicInfo = InvoiceBasicInfo::create([
            'oms_company_name' => config('oms.settings.oms_company_name'),
            'oms_company_tel' => config('oms.settings.oms_company_tel'),
            'oms_company_address' => config('oms.settings.oms_company_address'),
            'oms_company_vat' => config('oms.settings.oms_company_vat'),
            'oms_company_registration' => config('oms.settings.oms_company_registration'),
            'series' => "Quote",
            'invoice_notes' => config('oms.settings.invoice_notes'),
            'default_converted_status' => $status->id
        ]);

        $invoiceBasicInfo->addMedia(public_path('images/DemoLogo.png'))->preservingOriginal()->toMediaCollection("quote_logo");
        $invoiceBasicInfo->invoice_logo = $invoiceBasicInfo->getFirstMediaUrl('quote_logo');
        $invoiceBasicInfo->save();
    }
}
