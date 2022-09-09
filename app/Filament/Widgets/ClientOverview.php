<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Job;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ClientOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Invoices', Invoice::where('is_quote', false)->where('client_id', auth()->user()->id)->count()),
            Card::make('Quotes', Invoice::where('is_quote', false)->where('client_id', auth()->user()->id)->count()),
            Card::make('Jobs', Job::where('client_id', auth()->user()->id)->count()),

        ];
    }

    public static function canView(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if (auth()->user()->HasRole('Client')) {
            return true;
        }

        return false;
    }
}
