<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Models\Statement;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class StatementsRelationManager extends RelationManager
{
    protected static string $relationship = 'statements';

    protected static ?string $recordTitleAttribute = 'description';

    protected $listeners = [
        'refreshTable' => '$refresh',
    ];


    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->date()->label('Date'),
                Tables\Columns\TextColumn::make('description')->label('Description'),
                Tables\Columns\BadgeColumn::make('credit')
                    ->colors(['success'])
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->credit, 2);
                    })
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('debit')
                    ->colors(['danger'])
                    ->prefix('-R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->debit, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('opening_balance')
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->opening_balance, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('closing_balance')
                    ->prefix('R')
                    ->getStateUsing(function (Statement $record) {
                        return number_format($record->closing_balance, 2);
                    })
                    ->searchable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            TextFilter::make('description'),
            NumberFilter::make('credit'),
            NumberFilter::make('debit'),
            NumberFilter::make('opening_balance'),
            NumberFilter::make('closing_balance')
            ])
            ->headerActions([\AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export')
            ])
            ->actions([])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export')
            ]);
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
