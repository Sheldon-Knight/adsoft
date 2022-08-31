<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicePreviewController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', HomeController::class);

Route::get('/download/pdf-preview', InvoicePreviewController::class)->name('invoice-settings.downloadPdf');
Route::get('/download/pdf-preview/{record}', PdfController::class)->name('pdf-download');
