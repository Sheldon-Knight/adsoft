<?php

namespace App\Filament\Resources\QuoteStatusResource\Pages;

use App\Filament\Resources\QuoteStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuoteStatus extends EditRecord
{
    protected static string $resource = QuoteStatusResource::class;

    protected static ?string $title = 'Quotes Status';
    

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),           
        ];
    }
}
