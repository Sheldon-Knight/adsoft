<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Columns\Column;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([             
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-s-cog')
                    ->collapsed(),

                NavigationGroup::make()
                ->label('Finance')
                ->icon('heroicon-s-cash')
                ->collapsed(),
            ]);
        });

        Column::configureUsing(function (Column $column): void {
            $column
                ->toggleable()
                ->sortable();
        });

    }
}
