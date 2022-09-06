<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UserOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Clients', Client::count()),

            Card::make('My Instructions', auth()->user()->incompleteInstructions->count()),
            
            Card::make('Incomplete Jobs', auth()->user()->incompleteJobs->count()),

            Card::make('My Pending Leave Applications', auth()->user()->pendingLeaveApplications->count()),
        ];
    }

    public static function canView(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return true;
    }
}
