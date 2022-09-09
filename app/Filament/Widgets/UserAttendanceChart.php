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

    public string $presentLabel;

    public string $absentLabel;

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

    protected function getFilters(): ?array
    {
        return [
            'last_month' => 'Last Month',
            'this_month' => 'This Month',
            'month' => 'Month To Month',
            'year' => 'This Year',
        ];
    }

    protected function getHeading(): string
    {
        return 'My '.$this->presentLabel.' / '.$this->absentLabel.' Chart';
    }

    protected function getFilteredQuery($present = false)
    {
        $activeFilter = $this->filter;

        $query = Trend::query(Attendance::where('user_id', auth()->id())->where('present', $present))
        ->dateColumn('day')
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        if ($activeFilter == 'year') {
            $query = Trend::query(Attendance::where('user_id', auth()->id())->where('present', $present))
                ->dateColumn('day')
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perYear()
                ->count();
        }

        if ($activeFilter == 'this_month') {
            $query = Trend::query(Attendance::where('user_id', auth()->id())->where('present', $present))
                ->dateColumn('day')
                ->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )
                ->perDay()
                ->count();
        }

        if ($activeFilter == 'last_month') {
            $query = Trend::query(Attendance::where('user_id', auth()->id())->where('present', $present))
                ->dateColumn('day')
                ->between(
                    start: now()->subMonth(1)->startOfMonth(),
                    end: now()->subMonth(1)->endOfMonth(),
                )
                ->perDay()
                ->count();
        }

        return $query;
    }

    protected function getLabelsCount($presentAttendances, $absentAttendances): array
    {
        $countPresents = $presentAttendances->sum('aggregate');

        $countAbscents = $absentAttendances->sum('aggregate');

        if ($this->filter == 'month' or $this->filter == 'year') {
            $countPresents = $presentAttendances->sum('aggregate');

            $countAbscents = $absentAttendances->sum('aggregate');
        }

        return [
            'countPresents' => $countPresents,
            'countAbsents' => $countAbscents,
        ];
    }

    protected function getLabels($presentAttendances, $absentAttendances)
    {
        $counts = $this->getLabelsCount($presentAttendances, $absentAttendances);

        $activeFilter = $this->filter;

        $this->presentLabel = "Present Per Month ({$counts['countPresents']})";

        $this->absentLabel = "Abscents Per Month ({$counts['countAbsents']})";

        if ($activeFilter == 'last_month') {
            $this->presentLabel = "Present Last Month ({$counts['countPresents']})";

            $this->absentLabel = "Abscents Last Month ({$counts['countAbsents']})";
        }

        if ($activeFilter == 'this_month') {
            $this->presentLabel = "Present This Month ({$counts['countPresents']})";

            $this->absentLabel = "Abscents This Month ({$counts['countAbsents']})";
        }

        if ($activeFilter == 'year') {
            $this->presentLabel = "Present This Year ({$counts['countPresents']})";

            $this->absentLabel = "Abscents This Year ({$counts['countAbsents']})";
        }
    }

    protected function getData(): array
    {
        $presentAttendances = $this->getFilteredQuery(true);

        $absentAttendances = $this->getFilteredQuery();

        $this->getLabels($presentAttendances, $absentAttendances);

        $chart =
            [
                'datasets' => [
                    [
                        'label' => $this->presentLabel,
                        'data' => $presentAttendances->map(fn (TrendValue $value) => $value->aggregate),
                        'backgroundColor' => [
                            'rgba(0,255,0, 0.7)',
                        ],
                    ],
                    [
                        'label' => $this->absentLabel,
                        'data' => $absentAttendances->map(fn (TrendValue $value) => $value->aggregate),
                        'backgroundColor' => [
                            'rgba(255,0,0, 0.7)',
                        ],
                    ],
                ],
                'labels' => $presentAttendances->map(fn (TrendValue $value) => $value->date),
            ];

        return $chart;
    }
}
