<?php

namespace App\Providers;

use App\Models\OmsSetting;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\UserMenuItem;
use Filament\Tables\Columns\Column;
use Illuminate\Support\ServiceProvider;

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

                NavigationGroup::make()
                    ->label('My Workflow')
                    ->icon('heroicon-s-briefcase')
                    ->collapsed(),
            ]);
        });

        Column::configureUsing(function (Column $column): void {
            $column
                ->toggleable()
                ->sortable();
        });

        Filament::serving(function () {
            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()->url(route('filament.pages.profile')),
            ]);
        });

        $hasExpired = true;

        $getExpiredCache = cache()->get('hasExpired');

        if ($getExpiredCache === null) {
            cache()->forever('hasExpired', OmsSetting::first()->hasExpired());
            $hasExpired = cache()->get('hasExpired');
        }

        if ($hasExpired === false) {
            $subscription = cache()->get('subscription');

            $plan = cache()->get('current_plan');

            if (! $subscription) {
                cache()->forever('subscription', OmsSetting::first()->subscription);
            }

            if (! $plan) {
                cache()->forever('current_plan', OmsSetting::first()->subscription->plan->name);
            }
        }
    }
}
