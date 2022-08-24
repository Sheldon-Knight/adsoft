<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Models\Invoice;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected static ?string $title = 'Quotes';


    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Quotes',
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update quotes',$record);
    }
 

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Quote'),
        ];
    }
}
