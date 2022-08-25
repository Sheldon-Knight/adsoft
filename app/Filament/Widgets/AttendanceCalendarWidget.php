<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Flowframe\Trend\Trend;
use Saade\FilamentFullCalendar\Widgets\Concerns\CantManageEvents;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class AttendanceCalendarWidget extends FullCalendarWidget
{



    use CantManageEvents;

    protected static ?int $sort = 4;

    public function getViewData(): array
    {

        $attendances = Attendance::where("user_id", auth()->id())->get();

        $data = [];
        foreach ($attendances as $attendance) {
            $title = $attendance->present ? "Present" : "Abscent";
            $backgroundColor = $attendance->present ? "green" : "red";

            if ($title == "Present") {

                $data[] =
                    [
                        'id' => $attendance->id,
                        'title' => "Check In:" . $attendance->time_in,
                        'start' => $attendance->day,
                        "textColor" => '#fff',
                        "backgroundColor" => $backgroundColor,
                        "borderColor" => $backgroundColor,
                    ];

                $data[] =
                    [
                        'id' => $attendance->id,
                    'title' => "Check Out:" . $attendance->time_out,
                        'start' => $attendance->day,
                        "textColor" => '#fff',
                        "backgroundColor" => $backgroundColor,
                        "borderColor" => $backgroundColor,
                    ];





            }             else {

                $data[] =

                    [
                        'id' => $attendance->id,
                        'title' => $title,
                        'start' => $attendance->day,
                        "textColor" => '#fff',
                        "backgroundColor" => $backgroundColor,
                        "borderColor" => $backgroundColor,
                    ];
            }
        }



        return $data;
    }
}
