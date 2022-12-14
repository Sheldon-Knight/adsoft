<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\InstructionResource\Widgets\Comments;
use App\Filament\Resources\JobResource;
use Filament\Resources\Pages\ViewRecord;

class ViewJob extends ViewRecord
{
    protected static string $resource = JobResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            Comments::class,
        ];
    }
}
