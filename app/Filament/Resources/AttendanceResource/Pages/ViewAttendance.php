<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Filament\Widgets\AttendanceCalendarWidget;
use Filament\Resources\Pages\ViewRecord;

class ViewAttendance extends ViewRecord
{
    protected static string $resource = AttendanceResource::class;


    protected function getActions(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AttendanceCalendarWidget::class,
        ];
    }

}
