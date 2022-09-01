<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Job;
use App\Models\Status;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class MyJobs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-check';

    protected static string $view = 'filament.pages.my-jobs';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'My Workflow';

    protected function getTableQuery(): Builder
    {
        return Job::query()->where('user_id', auth()->id());
    }

    // protected static function getNavigationBadge(): ?string
    // {
    //     return auth()->user()->incompleteInstructions->count();
    // }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('client.client_name')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('department.name')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('invoice.invoice_number')->searchable(),
            \Filament\Tables\Columns\BadgeColumn::make('status.name')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('title')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('description')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('date_completed')
                ->date()->searchable(),
            \Filament\Tables\Columns\TextColumn::make('created_at')
                ->dateTime()->searchable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            MultiSelectFilter::make('status')->relationship('status', 'name'),
            MultiSelectFilter::make('client')->relationship('client', 'client_name'),
            MultiSelectFilter::make('deaprtment')->relationship('department', 'name'),
            TextFilter::make('title'),
            TextFilter::make('description'),
            DateFilter::make('date_completed'),
            DateFilter::make('created_at'),
            TrashedFilter::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            \Filament\Tables\Actions\EditAction::make('date_completed')
                ->label('Mark Complete')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('date_completed')
                        ->label('Completed At')
                        ->required(),

                    \Filament\Forms\Components\Select::make('status_id')
                        ->label('Status')
                        ->options(Status::pluck('name', 'id'))
                        ->required(),
                ])
                ->color('success')
                ->icon('heroicon-o-check')
                ->visible(function (Model $record) {
                    if ($record->date_completed != null) {
                        return false;
                    }

                    return true;
                }),

            \Filament\Tables\Actions\EditAction::make('status_id')
                ->label('Change Status')
                ->form([
                    \Filament\Forms\Components\Select::make('status_id')
                        ->label('Status')
                        ->options(Status::pluck('name', 'id'))
                        ->required(),
                ])
                ->visible(function (Model $record) {
                    if ($record->date_completed != null) {
                        return false;
                    }

                    return true;
                }),
            \Filament\Tables\Actions\EditAction::make('user_id')
                ->label('Reasign To')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\Select::make('user_id')
                        ->label('Assign To User')
                        ->options(User::pluck('name', 'id'))
                        ->required(),
                ])
                ->visible(function (Model $record) {
                    if ($record->date_completed != null) {
                        return false;
                    }

                    return true;
                }),
            ViewAction::make()->url(fn (Job $record): string => route('filament.resources.jobs.view', $record)),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return true;
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
                    'filament.resources.jobs.create'
                )
            )->visible(auth()->user()->can('create jobs')),
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
