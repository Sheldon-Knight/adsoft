<?php

namespace App\Filament\Pages\System;

use App\Models\OmsSetting;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class SystemUsers extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.system.system-users';

    public function mount()
    {
        return auth()->user()->is_admin == false ? abort(404) : '';
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->is_admin) {
            return true;
        } else {
            return false;
        }
    }

    protected function getTableQuery(): Builder
    {
        return User::query()->whereHas('roles', function ($q) {
            return $q->where('name', 'Super Admin');
        });
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name'),
            TextColumn::make('Package')->getStateUsing(function () {
                $omsSettings = OmsSetting::first();

                return $omsSettings->subscription?->plan->name ?? 'No Plan';
            }),
            BooleanColumn::make('Is Active')->getStateUsing(function () {
                $omsSettings = OmsSetting::find(1);

                return $omsSettings->subscription?->plan->name ? true : false;
            }),
            TextColumn::make('Expires At')->dateTime()->since()->getStateUsing(function () {
                $omsSettings = OmsSetting::first();

                return $omsSettings->subscription?->expired_at;
            }),
        ];
    }
}
