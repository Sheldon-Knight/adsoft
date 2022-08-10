<?php

namespace App\Filament\Resources\QuoteStatusResource\Pages;

use App\Filament\Resources\QuoteStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuoteStatuses extends ListRecords
{
    protected static string $resource = QuoteStatusResource::class;
    protected static ?string $title = 'Quotes Status';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Quote Status'),
        ];
    }
}
