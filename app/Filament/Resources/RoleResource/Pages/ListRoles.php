<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    public function mount(): void
    {    
        abort_unless(auth()->user()->can('view any roles'), 403);
    }
    
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
