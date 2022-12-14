<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use Filament\Resources\Pages\EditRecord;

class EditLeave extends EditRecord
{
    protected static string $resource = LeaveResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['revisioned_by'] = auth()->id();

        $data['revisioned_on'] = now();

        return $data;
    }

    protected function getActions(): array
    {
        return [

        ];
    }
}
