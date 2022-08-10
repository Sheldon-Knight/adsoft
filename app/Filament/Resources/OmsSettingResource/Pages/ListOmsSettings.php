<?php

namespace App\Filament\Resources\OmsSettingResource\Pages;

use App\Filament\Resources\OmsSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOmsSettings extends ListRecords
{
    protected static string $resource = OmsSettingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
