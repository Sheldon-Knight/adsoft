<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;     

    protected function getActions(): array
    {
        return [
      
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        return $data;
    }
}
