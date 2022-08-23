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
                    ->label('Banking')
                    ->icon('heroicon-s-credit-card')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('User Management')
                    ->icon('heroicon-s-user-group')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-s-cog')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Finance')
                    ->icon('heroicon-s-cash')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Jobs')
                    ->icon('heroicon-s-book-open')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Instructions')
                    ->icon('heroicon-s-switch-horizontal')
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
