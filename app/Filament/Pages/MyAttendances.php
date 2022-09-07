<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Widgets\AttendanceCalendarWidget;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Forms\Components\TimePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;

class MyAttendances extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static string $view = 'filament.pages.my-attendance';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'My Workflow';

    protected function getFooterWidgets(): array
    {
        return [
            AttendanceCalendarWidget::class,
        ];
    }



    protected function getTableQuery(): Builder
    {
        return Attendance::query()->where('user_id', auth()->id());
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return true;
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('day')->label('Date')->date()->searchable()->sortable(),
            BooleanColumn::make('present')->searchable()->sortable(),
            TextColumn::make('time_in')->dateTime('H:i:a')->searchable()->sortable(),
            TextColumn::make('time_out')->dateTime('H:i:a')->searchable()->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        // $q = Attendance::where('time_in',">=" ,"07:00:00")->where('time_in', "<=", "08:00:00")->get();
        // dd($q);
        return [
            SelectFilter::make('present')
                ->options([
                    0 => 'Absent',
                    1 => 'Present',
                ]),
            DateFilter::make('day'),

            Filter::make('time_in')
            ->form([
                TimePicker::make('time_in')->withoutSeconds(),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['time_in'],
                        fn (Builder $query, $date): Builder => $query->where('time_in', '=', Carbon::parse($date)->format('H:i:s')),
                    );
            }),

            Filter::make('time_out')
                ->form([
                    TimePicker::make('time_out')->withoutSeconds(),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['time_out'],
                            fn (Builder $query, $date): Builder => $query->where('time_out', '=', Carbon::parse($date)->format('H:i:s')),
                        );
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableBulkActions(): array
    {
        return [
            FilamentExportBulkAction::make('export'),
        ];
    }
}
