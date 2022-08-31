<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferResource\Pages;
use App\Models\Account;
use App\Models\Transfer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class TransferResource extends Resource
{
    protected static ?string $model = Transfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-switch-horizontal';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Banking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('from_account')
                    ->label('Transfer From:')
                    ->options(Account::all()->pluck('full_name', 'id')->toArray())
                    ->reactive()
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(fn (callable $set) => $set('to_account', null)),

                Select::make('to_account')
                    ->label('Transfer To:')
                    ->searchable()
                    ->reactive()
                    ->options(function (callable $get) {
                        $accounts = Account::where('id', '!=', $get('from_account'))->get();

                        if (! $accounts) {
                            return Account::all()->pluck('full_name', 'id')->toArray();
                        }

                        return $accounts->pluck('full_name', 'id')->toArray();
                    })
                    ->required(),

                TextInput::make('amount')
                    ->integer()
                    ->reactive()
                    ->minValue(1)
                    ->rule(function (callable $get) {
                        $account = Account::where('id', $get('from_account'))->first();
                        $balance = $account->balance;

                        return $account ? "max:{$balance}" : null;
                    }, fn (callable $get) => Account::where('id', $get('from_account'))->exists())
                    ->prefix('R')
                    ->mask(
                        fn (Mask $mask) => $mask
                            ->numeric()
                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                            ->decimalSeparator('.') // Add a separator for decimal numbers.
                            ->minValue(1) // Set the minimum value that the number can be.
                    )
                    ->required()
                    ->hiddenOn('edit'),
            ]);
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
                //
            ])
            ->actions([])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransfers::route('/'),
            'create' => Pages\CreateTransfer::route('/create'),
        ];
    }
}
