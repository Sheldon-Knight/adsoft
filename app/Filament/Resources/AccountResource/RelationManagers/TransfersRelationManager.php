<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Models\Transfer;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;

class TransfersRelationManager extends RelationManager
{
    protected static string $relationship = 'transfersFrom';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $title = 'Transfers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fromAccount.account_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('toAccount.account_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->prefix('R')
                    ->getStateUsing(function (Transfer $record) {
                        return number_format($record->amount, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Transfer Date')
                    ->dateTime()
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction.transaction_id')->searchable()
                    ->label('Transaction ID')->searchable(),
            ])
            ->filters([
                SelectFilter::make('from account')->relationship('fromAccount', 'account_number'),
                SelectFilter::make('to account')->relationship('toAccount', 'account_number'),
                Filter::make('transactions')
                    ->form([
                        TextInput::make('Transaction Id'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['Transaction Id'],
                                fn (Builder $query, $search): Builder => $query->whereHas('transaction', function (Builder $q) use ($search) {
                                    return  $q->where('transaction_id', 'LIKE', "%{$search}%");
                                }),
                            );
                    }),
                NumberFilter::make('amount'),
                DateFilter::make('created_at'),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export'),
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
