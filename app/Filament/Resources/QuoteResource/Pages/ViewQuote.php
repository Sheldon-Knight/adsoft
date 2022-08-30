<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewQuote extends ViewRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Quotes',
        ];
    }
    

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public static function canView(Model $record): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        };
        return true;
        // return auth()->user()->can('view quotes', $record);
    }
}
