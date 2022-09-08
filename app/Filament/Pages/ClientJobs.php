<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Status;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class ClientJobs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-alt';

    protected static string $view = 'filament.pages.client-jobs';

    protected static ?string $title = 'My Jobs';

    protected static ?string $navigationLabel = 'My Jobs';

    protected static ?string $slug = 'client/my-jobs';

    protected static ?string $navigationGroup = 'Summary';

    protected static ?int $navigationSort = 3;

    protected function getTableQuery(): Builder
    {
        return Job::query()->where('client_id', auth()->id());
    }

    public function mount()
    {
        if (! auth()->user()->Hasrole('Client')) {
            return abort(404);
        }
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return auth()->user()->Hasrole('Client');
    }

    protected function getTableColumns(): array
    {
        return [
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
            MultiSelectFilter::make('department')->relationship('department', 'name'),
            TextFilter::make('title'),
            TextFilter::make('description'),
            DateFilter::make('date_completed'),
            DateFilter::make('created_at'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make()
                ->form(
                    [
                        Select::make('user_id')
                        ->label('Assign To User')
                        ->required()
                            ->searchable()
                            ->options(User::query()->pluck('name', 'id')),

                        Select::make('created_by')
                        ->label('Created by User')
                        ->options(User::query()->pluck('name', 'id'))
                        ->visibleOn('view'),

                        DatePicker::make('created_at')
                        ->label('Created At')
                        ->visibleOn('view'),

                        DatePicker::make('date_completed')
                        ->label('Completed At')
                        ->visibleOn('view'),

                        Select::make('invoice_id')
                        ->label('Job Invoice')
                        ->required()
                            ->searchable()
                            ->options(Invoice::query()->where('is_quote', false)->pluck('invoice_number', 'id')),

                        Select::make('status_id')
                        ->label('Job Status')
                        ->required()
                            ->searchable()
                            ->options(Status::query()->pluck('name', 'id')),

                        TextInput::make('title')
                            ->required(),
                        Textarea::make('description')
                        ->required(),
                    ]
                ), ];
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
