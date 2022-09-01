<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;
    
    protected static ?string $title = 'Leave Application';
}