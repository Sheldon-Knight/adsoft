<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected static ?string $title = 'Leave Application';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['revisioned_by'] = auth()->id();

        $data['revisioned_on'] = now();

        $user = User::find($data['user_id']);

        $data['department_id'] = $user->department_id ?? null;

        return $data;
    }
}
