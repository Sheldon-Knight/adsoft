<?php

namespace App\Filament\Widgets;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Instruction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class incompleteInstruction extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return Instruction::query()->where('assigned_to', auth()->id())->where('date_completed', '=', null);
    }

    public static function canView(): bool
    {
        if (auth()->user()->is_admin == true) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if (auth()->user()->HasRole('Client')) {
            return false;
        }

        return true;
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('createdBy.name'),
            \Filament\Tables\Columns\TextColumn::make('assignedTo.name'),
            \Filament\Tables\Columns\TextColumn::make('title'),
            \Filament\Tables\Columns\TextColumn::make('instruction'),
            \Filament\Tables\Columns\TextColumn::make('due_date')
                ->date(),
            \Filament\Tables\Columns\TextColumn::make('date_completed')
                ->date(),
            \Filament\Tables\Columns\BooleanColumn::make('status')
                ->label('Completed')
                ->trueIcon('heroicon-o-badge-check')
                ->falseIcon('heroicon-o-x-circle'),
            \Filament\Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            DateFilter::make('date_completed'),
            DateFilter::make('created_at'),
            DateFilter::make('due_date'),
            TextFilter::make('instruction'),
            TextFilter::make('title'),
            SelectFilter::make('status')
                ->options([
                    0 => 'In-Completed',
                    1 => 'Completed',
                ]),
            MultiSelectFilter::make('created_by')->relationship('createdBy', 'name'),
            MultiSelectFilter::make('assigend_to')->relationship('assignedTo', 'name'),
            TrashedFilter::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make('date_completed')
                ->label('Mark Complete')
                ->form([
                    DatePicker::make('date_completed')
                        ->label('Completed At')
                        ->required(),
                ])
                ->color('success')
                ->icon('heroicon-o-check')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['status'] = true;

                    return $data;
                })

                ->visible(function (Model $record) {
                    if ($record->date_completed != null) {
                        return false;
                    }

                    return true;
                }),
            ViewAction::make()->url(fn (Instruction $record): string => route('filament.resources.instructions.view', $record)),

        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            FilamentExportBulkAction::make('export'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make()->url(
                route(
                    'filament.resources.instructions.create'
                )
            )->visible(auth()->user()->can('create instructions')),
            FilamentExportHeaderAction::make('export'),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 3;
    }

    protected function getTableFiltersFormWidth(): string
    {
        return '4xl';
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}
