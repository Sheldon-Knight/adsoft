<?php

namespace App\Filament\Resources\QuoteStatusResource\Pages;

use App\Filament\Resources\QuoteStatusResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuoteStatus extends CreateRecord
{
    protected static string $resource = QuoteStatusResource::class;

    protected static ?string $title = 'Quotes';


    protected function mutateFormDataBeforeCreate(array $data): array
    {    
        $data['is_quote'] = true;

        return $data;
    }


}
