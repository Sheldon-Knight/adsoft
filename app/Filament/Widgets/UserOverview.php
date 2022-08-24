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
          
        ];
    }
}
