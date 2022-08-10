<?php

namespace App\Filament\Resources\OmsSettingResource\Pages;

use App\Filament\Resources\OmsSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOmsSetting extends EditRecord
{
    protected static string $resource = OmsSettingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
