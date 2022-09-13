<?php

namespace App\Services;

use App\Models\Invoice as record;
use App\Models\OmsSetting;
use Carbon\Carbon;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class PdfInvoice
{
    public function GetAttachedInvoice(record $invoice, $isInvoice = true)
    {
        $omsSettings = OmsSetting::first();

        $seller = new Party([
            'name' => $omsSettings->oms_company_name,
            'phone' => $omsSettings->oms_company_tel,
            'address' => $omsSettings->oms_company_address,
            'custom_fields' => [
                'email' => $omsSettings->oms_company_email,
                'vat' => $omsSettings->oms_company_vat,
                'registration' => $omsSettings->oms_company_registration,
            ],
        ]);

        $buyer = new Buyer([
            'name' => $invoice->client->client_name,
            'phone' => $invoice->client->tel_num,
            'address' => $invoice->client->address,
            'custom_fields' => [
                'email' => $invoice->client->email,
            ],
        ]);

        foreach ($invoice->items as $invoiceItem) {
            $items[] = (new InvoiceItem())
                ->title($invoiceItem['item'])
                ->pricePerUnit($invoiceItem['price'])
                ->quantity($invoiceItem['qty'])
                ->discount(0);
        }

        $series = $isInvoice ? $omsSettings->invoice_series : $omsSettings->quote_series;
        $notes = $isInvoice ? $omsSettings->invoice_notes : $omsSettings->quote_notes;
        $name = $isInvoice ? 'invoice' : 'quote';

        Invoice::make("{$series} # {$invoice->invoice_number}")
            ->series($series)
            ->status($invoice->invoice_status)
            ->seller($seller)
            ->buyer($buyer)
            ->date(Carbon::parse($invoice->invoice_date))
            ->dateFormat($omsSettings->date_format)
            ->currencySymbol('R')
            ->currencyCode('ZAR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->addItems($items)
            ->notes($notes)
            ->filename("client/{$invoice->client_id}/file/$invoice->id/$name")
            ->setCustomData(['invoice_due_date' => $invoice->invoice_due_date, 'invoice_number' => $invoice->invoice_number])
            ->logo(public_path('/storage/'.$omsSettings->oms_logo))
            ->save('public');

        $file = public_path("storage/client/{$invoice->client_id}/file/$invoice->id/$name.pdf");

        return $file;
    }

    public function downloadPDf(record $invoice, $isInvoice = true)
    {
        $omsSettings = OmsSetting::first();

        $seller = new Party([
            'name' => $omsSettings->oms_company_name,
            'phone' => $omsSettings->oms_company_tel,
            'address' => $omsSettings->oms_company_address,
            'custom_fields' => [
                'email' => $omsSettings->oms_company_email,
                'vat' => $omsSettings->oms_company_vat,
                'registration' => $omsSettings->oms_company_registration,
            ],
        ]);

        $buyer = new Buyer([
            'name' => $invoice->client->client_name,
            'phone' => $invoice->client->tel_num,
            'address' => $invoice->client->address,
            'custom_fields' => [
                'email' => $invoice->client->email,

            ],
        ]);

        foreach ($invoice->items as $invoiceItem) {
            $items[] = (new InvoiceItem())
                ->title($invoiceItem['item'])
                ->pricePerUnit($invoiceItem['price'])
                ->quantity($invoiceItem['qty'])
                ->discount(0);
        }

        $series = $isInvoice ? $omsSettings->invoice_series : $omsSettings->quote_series;
        $notes = $isInvoice ? $omsSettings->invoice_notes : $omsSettings->quote_notes;
        $name = $isInvoice ? 'invoice' : 'quote';

        $pdfInvoice = Invoice::make("{$series} # {$invoice->invoice_number}")
            ->series($series)
            ->status($invoice->invoice_status)
            ->seller($seller)
            ->buyer($buyer)
            ->date(Carbon::parse($invoice->invoice_date))
            ->dateFormat($omsSettings->date_format)
            ->currencySymbol('R')
            ->currencyCode('ZAR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->addItems($items)
            ->notes($notes)
            ->filename("client/{$invoice->client_id}/file/$invoice->id/$name")
            ->setCustomData(['invoice_due_date' => $invoice->invoice_due_date, 'invoice_number' => $invoice->invoice_number])
            ->logo(public_path('/storage/'.$omsSettings->oms_logo))
            ->save('public');

        $file = public_path("storage/client/{$invoice->client_id}/file/$invoice->id/$name.pdf");

        return $pdfInvoice->download();
    }

    public function streamPDf(record $invoice, $isInvoice = true)
    {
        $omsSettings = OmsSetting::first();

        $seller = new Party([
            'name' => $omsSettings->oms_company_name,
            'phone' => $omsSettings->oms_company_tel,
            'address' => $omsSettings->oms_company_address,
            'custom_fields' => [
                'email' => $omsSettings->oms_company_email,
                'vat' => $omsSettings->oms_company_vat,
                'registration' => $omsSettings->oms_company_registration,
            ],
        ]);

        $buyer = new Buyer([
            'name' => $invoice->client->client_name,
            'phone' => $invoice->client->tel_num,
            'address' => $invoice->client->address,
            'custom_fields' => [
                'email' => $invoice->client->email,

            ],
        ]);

        foreach ($invoice->items as $invoiceItem) {
            $items[] = (new InvoiceItem())
                ->title($invoiceItem['item'])
                ->pricePerUnit($invoiceItem['price'])
                ->quantity($invoiceItem['qty'])
                ->discount(0);
        }

        $series = $isInvoice ? $omsSettings->invoice_series : $omsSettings->quote_series;
        $notes = $isInvoice ? $omsSettings->invoice_notes : $omsSettings->quote_notes;
        $name = $isInvoice ? 'invoice' : 'quote';

        $pdfInvoice = Invoice::make("{$series} # {$invoice->invoice_number}")
        ->series($series)
            ->status($invoice->invoice_status)
            ->seller($seller)
            ->buyer($buyer)
            ->date(Carbon::parse($invoice->invoice_date))
            ->dateFormat($omsSettings->date_format)
            ->currencySymbol('R')
            ->currencyCode('ZAR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->addItems($items)
            ->notes($notes)
            ->filename("client/{$invoice->client_id}/file/$invoice->id/$name")
            ->setCustomData(['invoice_due_date' => $invoice->invoice_due_date, 'invoice_number' => $invoice->invoice_number])
            ->logo(public_path('/storage/'.$omsSettings->oms_logo))
            ->save('public');

        $file = public_path("storage/client/{$invoice->client_id}/file/$invoice->id/$name.pdf");

        return $pdfInvoice->stream();
    }
}
