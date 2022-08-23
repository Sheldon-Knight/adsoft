<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Wizard\Step;



class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;     

    protected function getActions(): array
    {
        return [
      
        ];
    }
}
