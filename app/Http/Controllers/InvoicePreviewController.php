<?php

namespace App\Http\Controllers;

use App\Models\InvoiceBasicInfo;
use App\Models\OmsSetting;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

class InvoicePreviewController extends Controller
{
    public function __invoke(Request $request)
    {
        $omsSettings = OmsSetting::first();

        $seller = new Party([
            'name'          => $omsSettings->oms_company_name,
            'phone'         => $omsSettings->oms_company_tel,
            'address' => $omsSettings->oms_company_address,
            'custom_fields' => [
                'email' => $omsSettings->oms_company_email,
                'vat' => $omsSettings->oms_company_vat,
                'registration' => $omsSettings->oms_company_registration,
            ],
        ]);



        $buyer = new Buyer([
            'name'          => "John Doe",
            'phone'         => '012-345-6789',
            'address' => "Random Street, Random City, Random Country",
            'custom_fields' => [
                'email' => 'test@example.com',
                'vat' => '123456789',
                'registration' => '987654321'
            ],
        ]);

        $items = [
            (new InvoiceItem())->title('Item 1')->pricePerUnit(71.96)->quantity(2),
            (new InvoiceItem())->title('Item 2')->pricePerUnit(92.82)->quantity(1),
            (new InvoiceItem())->title('Item 3')->pricePerUnit(12.98)->quantity(3),
            (new InvoiceItem())->title('Item 4')->pricePerUnit(2.80)->quantity(4),
            (new InvoiceItem())->title('Item 5')->pricePerUnit(56.21)->quantity(1),
        ];


        $preview = Invoice::make('Inv # ' . 1)
            ->series($omsSettings->invoice_series)
            ->status("Unpaid")
            ->seller($seller)
            ->buyer($buyer)
            ->date(today())
            ->dateFormat($omsSettings->date_format)
            ->payUntilDays(intval(now()->format('d')))
            ->currencySymbol('R')
            ->currencyCode('ZAR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->addItems($items)
            ->notes($omsSettings->invoice_notes)
            ->setCustomData(['invoice_due_date' => now()->addDays(10)->format($omsSettings->date_format)])
            ->logo(public_path('/storage/' . $omsSettings->oms_logo));


        return $preview->stream();
    }
}
