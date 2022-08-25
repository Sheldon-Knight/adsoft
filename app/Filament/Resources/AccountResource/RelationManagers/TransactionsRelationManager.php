<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Models\Transaction;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\TextFilter;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id'),
                Tables\Columns\TextColumn::make('account.account_number'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'credit',
                        'danger' => 'debit',
                    ]),
                Tables\Columns\TextColumn::make('amount')
                    ->prefix('R')
                    ->getStateUsing(function (Transaction $record) {
                        return number_format($record->amount, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Transaction Date")
                    ->dateTime(),
            ])
            ->filters([
                DateFilter::make('created_at'),
                TextFilter::make('transaction_id'),
                TextFilter::make('description'),
                NumberFilter::make('amount'),
                SelectFilter::make('account')->relationship('account', 'account_number'),
                SelectFilter::make('type')->options([
                    'debit' => 'debit',
                    'credit' => 'credit',
                ])

            ])
            ->headerActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export')
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
