<?php

namespace App\Http\Controllers;

use App\Models\InvoiceBasicInfo;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoicePreviewController extends Controller
{  
    public function __invoke(Request $request)
    {
        $invoiceBasicInfo = InvoiceBasicInfo::find(1);

        $data= [
            'oms_company_name' => $invoiceBasicInfo->oms_company_name,
            'oms_company_address' => $invoiceBasicInfo->oms_company_address,
            'oms_company_tel' => $invoiceBasicInfo->oms_company_tel,
            'oms_company_email' => $invoiceBasicInfo->oms_company_email,
            'oms_company_vat' => $invoiceBasicInfo->oms_company_vat,
            'oms_company_registration' => $invoiceBasicInfo->oms_company_registration,
            'invoice_notes' => $invoiceBasicInfo->invoice_notes,
            'date_format' => $invoiceBasicInfo->date_format,
            'series' => $invoiceBasicInfo->series,
            'invoice_logo' => $invoiceBasicInfo->invoice_logo,
        ];      

        dd($data);

        $customer = new Buyer([
            'name'          => 'John Doe',
            'custom_fields' => [
                'email' => 'test@example.com',
            ],
        ]);

        $item = (new InvoiceItem())->title('Service 1')->pricePerUnit(2);

        $invoice = Invoice::make()
            ->buyer($customer)
            ->discountByPercent(10)
            ->taxRate(15)
            ->shipping(1.99)
            ->addItem($item);

        return $invoice->download();
    }
}
