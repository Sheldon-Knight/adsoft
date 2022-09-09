<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\Concerns\CantManageEvents;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class AttendanceCalendarWidget extends FullCalendarWidget
{
    use CantManageEvents;

    public ?Model $record = null;

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if (auth()->user()->HasRole('Client')) {
            return false;
        }

        if (request()->routeIs('filament.pages.dashboard')) {
            return false;
        }

        return true;
    }

    public function getViewData(): array
    {
        $attendances = Attendance::where('user_id', $this->record->user_id ?? auth()->id())->get();

        $data = [];
        foreach ($attendances as $attendance) {
            $title = $attendance->present ? 'Present' : 'Abscent';
            $backgroundColor = $attendance->present ? 'green' : 'red';
            $pendingColor = 'orange';

            if ($title == 'Present') {
                if ($attendance->time_in) {
                    $data[] =
                        [
                            'id' => $attendance->id,
                            'title' => 'Check In: '.Carbon::parse($attendance->time_in)->format('H:i:a'),
                            'start' => $attendance->day,
                            'textColor' => '#fff',
                            'backgroundColor' => $backgroundColor,
                            'borderColor' => $backgroundColor,
                        ];
                } else {
                    $data[] =
                        [
                            'id' => $attendance->id,
                            'title' => 'Not Checked In',
                            'start' => $attendance->day,
                            'textColor' => '#fff',
                            'backgroundColor' => $pendingColor,
                            'borderColor' => $pendingColor,
                        ];
                }

                if ($attendance->time_out) {
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
                            'title' => 'Not Checked Out',
                            'start' => $attendance->day,
                            'textColor' => '#fff',
                            'backgroundColor' => $pendingColor,
                            'borderColor' => $pendingColor,
                        ];
                }
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
