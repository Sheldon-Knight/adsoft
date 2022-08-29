<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\PdfInvoice;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function __invoke(Request $request, Invoice $record)
    {
        $pdfInvoice = new PdfInvoice();

        if ($record->is_quote == true) {
            return $pdfInvoice->downloadPDf($record, $isInvoice = false);
        } else {
            return $pdfInvoice->downloadPDf($record);
        }
    }
}
