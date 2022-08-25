<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Models\Attendance;
use Filament\Forms\Components\DatePicker;
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

    protected function getTableQuery(): Builder
    {
        return Attendance::query()->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('day')->label("Date")->date()->searchable()->sortable(),
            BooleanColumn::make('present')->searchable()->sortable(),
            TextColumn::make('time_in')->dateTime("H:i:a")->searchable()->sortable(),
            TextColumn::make('time_out')->dateTime("H:i:a")->searchable()->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('present')
                ->options([
                    0 => 'Absent',
                    1 => 'Present',
                ]),

            DateFilter::make('day'),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableBulkActions(): array
    {
        return [
            FilamentExportBulkAction::make('export')
        ];
    }
}
