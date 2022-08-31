<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected static ?string $title = 'Quotes';

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableFiltersFormWidth(): string
    {
        return '4xl';
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Quotes',
        ];
    }

    public static function canEdit(Model $record): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return auth()->user()->can('update quotes', $record);
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Quote'),
        ];
    }
}
