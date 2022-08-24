<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\BarChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserAttendanceChart extends BarChartWidget
{

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public ?string $filter = 'month';


    protected function getFilters(): ?array
    {
        return [
            'day' => 'Day To Day',
            'month' => 'Month To Month',
            'year' => 'This Year',
        ];
    }


    protected function getHeading(): string
    {
        return 'My Present/Absent Attendances Counts Chart';
    }




    protected function getFilteredQuery($present = false)
    {
        $activeFilter = $this->filter;

        $query = Trend::query(Attendance::where('user_id', auth()->id())->where('present', $present))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        if ($activeFilter == 'year') {
            $query = Trend::query(Attendance::where('user_id', auth()->id())->where('present', $present))
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perYear()
                ->count();
        }

        if ($activeFilter == 'day') {
            $query = Trend::query(Attendance::where('user_id', auth()->id())->where('present', $present))
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perDay()
                ->count();
        }

        return $query;
    }



    protected function getData(): array
    {
     
        $presentAttendances = $this->getFilteredQuery(true);   

        $absentAttendances = $this->getFilteredQuery();
           

        $chart =
            [
                'datasets' => [
                    [
                        'label' => 'Present Per ' . ucfirst($this->filter),
                        'data' => $presentAttendances->map(fn (TrendValue $value) => $value->aggregate),
                        'backgroundColor' =>  [
                            'rgba(0,255,0, 0.7)'
                        ],
                    ],
                    [
                        'label' => 'Absent Per ' . ucfirst($this->filter),
                        'data' => $absentAttendances->map(fn (TrendValue $value) => $value->aggregate),
                        'backgroundColor' =>  [
                            'rgba(255,0,0, 0.7)'
                        ],
                    ],
                ],
                'labels' => $presentAttendances->map(fn (TrendValue $value) => $value->date),
            ];


        return $chart;
    }
}
