<?php

namespace App\Filament\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Invoice;
use Filament\Pages\Page;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class ClientQuotes extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static string $view = 'filament.pages.client-quotes';

    protected static ?string $title = 'My Quotes';

    protected static ?string $navigationLabel = 'My Quotes';

    protected static ?string $slug = 'client/my-quotes';

    protected static ?string $navigationGroup = 'Summary';

    protected static ?int $navigationSort = 1;

    protected function getTableQuery(): Builder
    {
        return Invoice::query()->where('is_quote', true)->where('client_id', auth()->id());
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
            \Filament\Tables\Columns\TextColumn::make('client.name')->searchable(),
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
            MultiSelectFilter::make('client')->relationship('client', 'name'),
            MultiSelectFilter::make('department')->relationship('department', 'name'),
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
            ViewAction::make(),
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
