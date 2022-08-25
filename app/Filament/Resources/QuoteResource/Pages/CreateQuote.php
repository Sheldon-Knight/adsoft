<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Models\Status;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;

    protected static ?string $title = 'Quotes';

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Quotes',
        ];
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $data['is_quote'] = true;

        $data['invoice_status'] = Status::find($data['invoice_status'])->first()->name ?? "";

        return $data;
    }
}
