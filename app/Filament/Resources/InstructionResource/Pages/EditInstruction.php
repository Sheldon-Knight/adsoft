<?php

namespace App\Filament\Resources\InstructionResource\Pages;

use App\Filament\Resources\InstructionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstruction extends EditRecord
{
    protected static string $resource = InstructionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
