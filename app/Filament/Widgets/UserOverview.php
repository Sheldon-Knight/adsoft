<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UserOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            Card::make('Clients', User::role('Client')->count()),

            Card::make('Incomplete Instructions', auth()->user()->incompleteInstructions->count()),

            Card::make('Incomplete Jobs', auth()->user()->incompleteJobs->count()),

            Card::make('My Pending Leave Applications', auth()->user()->pendingLeaveApplications->count()),
        ];
    }

    public static function canView(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if (auth()->user()->HasRole("Client")) {
            return false;
        }

        return true;
    }
}
