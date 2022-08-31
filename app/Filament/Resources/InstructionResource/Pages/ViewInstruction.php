<?php

namespace App\Filament\Resources\InstructionResource\Pages;

use App\Filament\Resources\InstructionResource;
use App\Filament\Resources\InstructionResource\Widgets\Comments;
use Filament\Resources\Pages\ViewRecord;

class ViewInstruction extends ViewRecord
{
    protected static string $resource = InstructionResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            Comments::class,
        ];
    }
}
