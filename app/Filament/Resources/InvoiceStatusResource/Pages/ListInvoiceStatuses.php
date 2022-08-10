<?php

namespace App\Filament\Resources\InvoiceStatusResource\Pages;

use App\Filament\Resources\InvoiceStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoiceStatuses extends ListRecords
{
    protected static string $resource = InvoiceStatusResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
