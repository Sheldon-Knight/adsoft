<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers\StatementsRelationManager;
use App\Filament\Resources\AccountResource\RelationManagers\TransactionsRelationManager;
use App\Filament\Resources\AccountResource\RelationManagers\TransfersRelationManager;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Transfer;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentAdvancedFilter\Filters\NumberFilter;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Banking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('account_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_number')
                    ->integer(),
                Forms\Components\TextInput::make('bank_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('balance')
                    ->integer()
                    ->prefix('R')
                    ->mask(
                        fn (Mask $mask) => $mask
                            ->numeric()
                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                            ->decimalSeparator('.') // Add a separator for decimal numbers.
                            ->minValue(0) // Set the minimum value that the number can be.
                    )
                    ->required()
                    ->hiddenOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('account_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('bank_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('balance')->searchable()->sortable()
                    ->prefix('R')
                    ->getStateUsing(function (Account $record) {
                        return number_format($record->balance, 2);
                    }),

            ])
            ->filters([
                MultiSelectFilter::make('account_name')
                    ->options(Account::pluck('account_name', 'account_name')->toArray())
                    ->column('account_name'),

                MultiSelectFilter::make('account_number')
                    ->options(Account::pluck('account_number', 'account_number')->toArray())
                    ->column('account_number'),

                MultiSelectFilter::make('bank_name')
                    ->options(Account::pluck('bank_name', 'bank_name')->toArray())
                    ->column('bank_name'),

                NumberFilter::make('balance'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('add_funds')
                    ->label('Add Funds')
                    ->color('success')
                    ->visible(fn (Account $record): bool => auth()->user()->can('add funds to accounts', $record))
                    ->action(function ($data, Model $record) {
                        $transaction = Transaction::create([
                            'transaction_id' => str()->uuid(),
                            'account_id' => $record->id,
                            'description' => 'R' . number_format($data['amount'], 2) . ' Has Been Added To Your Account',
                            'type' => 'credit',
                            'amount' => $data['amount'],
                        ]);

                        Notification::make()
                            ->title('R' . number_format($data['amount'], 2) . ' Has Been Added To Account: ' . $record->account_number)
                            ->success()
                            ->duration(5000)
                            ->persistent()
                            ->send();
                    })->form([
                        Card::make()
                            ->schema([
                                TextInput::make('current_balance')
                                    ->integer()
                                    ->prefix('R')
                                    ->placeholder(function (model $record) {
                                        return number_format($record->balance, 2);
                                    })
                                    ->mask(
                                        fn (Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                                            ->decimalSeparator('.') // Add a separator for decimal numbers.
                                            ->minValue(0) // Set the minimum value that the number can be.
                                    )
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan('full'),

                                TextInput::make('amount')
                                    ->integer()
                                    ->reactive()
                                    ->minValue(1)
                                    ->afterStateUpdated(function (Closure $set, $state, Model $record) {
                                        $newValue = strval($record->balance + $state);

                                        $set('new_balance', number_format($newValue, 2));
                                    })
                                    ->prefix('R')
                                    ->mask(
                                        fn (Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                                            ->decimalSeparator('.') // Add a separator for decimal numbers.
                                            ->minValue(1) // Set the minimum value that the number can be.
                                    )
                                    ->required(),

                                TextInput::make('new_balance')
                                    ->integer()
                                    ->prefix('R')
                                    ->default(function (model $record) {
                                        return number_format($record->balance, 2);
                                    })
                                    ->mask(
                                        fn (Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                                            ->decimalSeparator('.') // Add a separator for decimal numbers.
                                            ->minValue(0) // Set the minimum value that the number can be.
                                    )
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan('full'),
                            ]),
                    ]),

                Tables\Actions\Action::make('Transfer')
                    ->color('secondary')
                    ->visible(function (Model $record) {
                        if (auth()->user()->can('create transfers', $record) and $record->balance >= 1) {
                            return true;
                        }

                        return true;
                    })
                    ->label('Transfer')
                    ->icon('heroicon-s-switch-vertical')
                    ->action(function ($data, Model $record) {
                        $data['from_account'] = $record->id;

                        Transfer::create($data);

                        $toAccount = Account::find($data['to_account']);

                        Notification::make()
                            ->title('Transfer Succesfull')
                            ->body('Transfer of R' . number_format($data['amount'], 2) . ' has been made from acccount: ' . $record->account_number . ' to account: ' . $toAccount->account_number)
                            ->duration(5000)
                            ->persistent()
                            ->success()
                            ->send();
                    })->form([
                        Card::make()
                            ->schema([
                                TextInput::make('current_balance')
                                    ->integer()
                                    ->prefix('R')
                                    ->placeholder(function (model $record) {
                                        return number_format($record->balance, 2);
                                    })
                                    ->mask(
                                        fn (Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                                            ->decimalSeparator('.') // Add a separator for decimal numbers.
                                            ->minValue(0) // Set the minimum value that the number can be.
                                    )
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan('full'),

                                Select::make('to_account')
                                    ->label('Transfer To:')
                                    ->searchable()
                                    ->reactive()
                                    ->options(function (Model $record) {
                                        $accounts = Account::where('id', '!=', $record->id)->get();

                                        return $accounts->pluck('full_name', 'id')->toArray();
                                    })
                                    ->required(),

                                TextInput::make('amount')
                                    ->integer()
                                    ->reactive()
                                    ->minValue(1)
                                    ->rule(function (model $record) {
                                        $balance = $record->balance;

                                        return $record->balance ? "max:{$balance}" : null;
                                    })
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
                            ])

                            ->columns(2),
                    ]),

                Tables\Actions\DeleteAction::make()->visible(function (Account $record) {
                    if ($record->deleted_at != null) {
                        return false;
                    }

                    return auth()->user()->can('delete accounts', $record);
                }),

                Tables\Actions\RestoreAction::make()->visible(function (Account $record) {
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('restore accounts', $record);
                }),

                Tables\Actions\ForceDeleteAction::make()->visible(function (Account $record) {
                    if ($record->deleted_at === null) {
                        return false;
                    }

                    return auth()->user()->can('force delete accounts', $record);
                }),
            ])
            ->headerActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction::make('export'),
            ])
            ->bulkActions([
                \AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction::make('export'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StatementsRelationManager::class,
            TransfersRelationManager::class,
            TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
            'view' => Pages\ViewAccount::route('/view/{record}'),
        ];
    }

    public static function getEloquentQuery(): EloquentBuilder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
