<?php

namespace App\Filament\Resources\InvoiceStatusResource\Pages;

use App\Filament\Resources\InvoiceStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoiceStatus extends ViewRecord
{
    protected static string $resource = InvoiceStatusResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
