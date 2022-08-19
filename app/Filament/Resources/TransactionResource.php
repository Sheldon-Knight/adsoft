<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Account;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-expand';

    protected static ?string $navigationGroup = 'Banking';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('account_id')
                    ->label('Account')
                    ->options(Account::all()->pluck('account_number', 'id')->toArray())
                    ->reactive()
                    ->required()
                    ->searchable(),

                Select::make('type')
                    ->label('Type')
                    ->options(["credit" => "Credit", "debit" => "Debit"])
                    ->reactive()
                    ->required()
                    ->searchable(),

                TextInput::make('transaction_id')
                    ->label('Transaction Id')
                    ->default(str()->uuid())
                    ->required()
                    ->disabled()
                    ->columnSpan('full'),

                Textarea::make('description')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),

                TextInput::make('amount')
                    ->integer()
                    ->reactive()
                    ->minValue(1)
                    ->prefix('R')
                    ->mask(
                        fn (Mask $mask) => $mask
                            ->numeric()
                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                            ->decimalSeparator('.') // Add a separator for decimal numbers.                                             
                            ->minValue(1) // Set the minimum value that the number can be.                     
                    )
                    ->required()
                    ->hiddenOn('view'),

                TextInput::make('amount_1')
                    ->label("Amount")
                    ->integer()
                    ->reactive()
                    ->minValue(1)
                    ->prefix('R')
                    ->placeholder(function (Model $record) {
                   
                       return number_format($record->amount / 100,2);
                    })                    
                    ->required()
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([Tables\Columns\TextColumn::make('transaction_id'),
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
                        return number_format($record->amount / 100, 2);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Transaction Date")
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
               
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/view/{record}'),
        ];
    }
}
