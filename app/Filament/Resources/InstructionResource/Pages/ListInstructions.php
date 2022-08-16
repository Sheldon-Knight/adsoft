<?php

namespace App\Filament\Resources\InstructionResource\Pages;

use App\Filament\Resources\InstructionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstructions extends ListRecords
{
    protected static string $resource = InstructionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
