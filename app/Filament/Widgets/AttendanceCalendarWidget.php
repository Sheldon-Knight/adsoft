<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Saade\FilamentFullCalendar\Widgets\Concerns\CantManageEvents;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class AttendanceCalendarWidget extends FullCalendarWidget
{
    use CantManageEvents;

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return true;
    }

    public function getViewData(): array
    {
        $attendances = Attendance::where('user_id', auth()->id())->get();

        $data = [];
        foreach ($attendances as $attendance) {
            $title = $attendance->present ? 'Present' : 'Abscent';
            $backgroundColor = $attendance->present ? 'green' : 'red';

            if ($title == 'Present') {
                $data[] =
                    [
                        'id' => $attendance->id,
                        'title' => 'Check In: '.Carbon::parse($attendance->time_in)->format('H:i:a'),
                        'start' => $attendance->day,
                        'textColor' => '#fff',
                        'backgroundColor' => $backgroundColor,
                        'borderColor' => $backgroundColor,
                    ];

                $data[] =
                    [
                        'id' => $attendance->id,
                        'title' => 'Check Out: '.Carbon::parse($attendance->time_out)->format('H:i:a'),
                        'start' => $attendance->day,
                        'textColor' => '#fff',
                        'backgroundColor' => $backgroundColor,
                        'borderColor' => $backgroundColor,
                    ];
            } else {
                $data[] =

                    [
                        'id' => $attendance->id,
                        'title' => $title,
                        'start' => $attendance->day,
                        'textColor' => '#fff',
                        'backgroundColor' => $backgroundColor,
                        'borderColor' => $backgroundColor,
                    ];
            }
        }

        return $data;
    }
}
