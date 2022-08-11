<?php

namespace App\Http\Controllers;

use App\Models\InvoiceBasicInfo;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

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

 
        $seller = new Party([
            'name'          => $invoiceBasicInfo->oms_company_name,
            'phone'         => $invoiceBasicInfo->oms_company_tel,
            'address' => $invoiceBasicInfo->oms_company_address,   
            'custom_fields' => [              
                          
                'email' => $invoiceBasicInfo->oms_company_email,
                'vat' => $invoiceBasicInfo->oms_company_vat,
                'registration' => $invoiceBasicInfo->oms_company_registration,             
 
         
            ],
        ]);
        

        $customer = new Buyer([           
            'name'          => "John Doe",
            'phone'         => '012-345-6789',
            'address' => "Random Street, Random City, Random Country",   
            'custom_fields' => [
                'email' => 'test@example.com',        
                'vat' => '123456789',
                'registration' => '987654321' 
            ],
        ]);

        $item = (new InvoiceItem())->title('Fake Item 1')->pricePerUnit(1);

        $invoice = Invoice::make()
            ->buyer($customer)
            ->seller($seller)
            ->discountByPercent(10)
            ->taxRate(15)
            ->shipping(1.99)
            ->addItem($item);

        return $invoice->download();
    }
}
