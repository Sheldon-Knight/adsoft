<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Models\Invoice;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected static ?string $title = 'Quotes';
   

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Quote'),
        ];
    }
}
