<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Models\Account;
use App\Models\Statement;
use App\Models\Transaction;
use App\Models\Transfer;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

use Illuminate\Database\Eloquent\Model;

class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    public $currentRouteName;




    public function __construct()
    {
        $this->currentRouteName = url()->current();
    }

    protected $listeners = [
        'refreshTable' => 'refreshPage',
    ];


    public function refreshPage()
    {
        return redirect()->to($this->currentRouteName);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['balance'] = number_format($data['balance'] / 100, 2);

        return $data;
    }

    protected function getActions(): array
    {
        return [
            Action::make("add_funds")
                ->label("Add Funds")
                ->color('success')
                ->action(function ($data) {
                    $record = $this->record;

                    $openingBalance = $record->balance;
                    $closingBalance = $record->balance + $data['amount'] * 100;


                    $record->balance += $data['amount'] * 100;

                    $record->save();

                    $transaction = Transaction::create([
                        'transaction_id' => str()->uuid(),
                        'account_id' => $record->id,
                        'description' => "R" . number_format($data['amount'], 2) . " Has Been Added To Your Account",
                        'type' => 'credit',
                        'amount' => $data['amount'] * 100,
                    ]);

                    $account = $record;

                    $statement = Statement::create([
                        'account_id' => $account->id,
                        'transaction_id' => $transaction->id,
                        'description' => $transaction->description,
                        'debit' => 0,
                        'credit' => $data['amount'] * 100,
                        'opening_balance' => $openingBalance,
                        'closing_balance' => $closingBalance,
                    ]);

                    $statement->update([
                        'closing_balance' => $account->balance,
                    ]);

                    Notification::make()
                        ->title('R' . number_format($data['amount'], 2) . ' Has Been Added To Account: ' . $record->account_number)
                        ->success()
                        ->duration(5000)
                        ->persistent()
                        ->send();

                    $this->emit('refreshTable');
                })->form([
                    Card::make()
                        ->schema([
                            TextInput::make('current_balance')
                                ->integer()
                                ->prefix('R')
                                ->placeholder(function (model $record) {
                                    return number_format($record->balance / 100, 2);
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
                                    $newValue = strval($record->balance + $state * 100);

                                    $set('new_balance', number_format($newValue / 100, 2));
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
                                ->default(function (Model $record) {
                                    return number_format($record->balance / 100, 2);
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
            Action::make("transfer")
                ->label("Make Transfer")
                ->color('primary')
                ->action(function ($data) {
                    $record = $this->record;

                    $data['from_account'] = $record->id;
                    $data['amount'] *= 100;

                    Transfer::create($data);

                    $toAccount = Account::find($data['to_account']);

                    Notification::make()
                        ->title("Transfer Succesfull")
                        ->body("Transfer of R" . number_format($data['amount'] / 100, 2) . " has been made from acccount: " . $record->account_number . " to account: " . $toAccount->account_number)
                        ->duration(8000)
                        ->persistent()
                        ->success()
                        ->send();

                    $this->emit('refreshTable');
                })
                ->form([
                    Select::make('to_account')
                        ->label('Transfer To:')
                        ->searchable()
                        ->reactive()
                        ->options(function () {
                            $accounts = Account::where('id', '!=', $this->record->id)->get();

                            return $accounts->pluck('full_name', 'id')->toArray();
                        })
                        ->required(),

                    TextInput::make('amount')
                        ->integer()
                        ->reactive()
                        ->minValue(1)
                        ->rule(function () {
                            $account = Account::where('id', '!=', $this->record->id)->get();
                            $balance = $this->record->balance / 100;
                            return $account ? "max:{$balance}" : null;
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
                ]),

            Action::make('Transact')
                ->color('secondary')
                ->label("Transact")
                ->icon("heroicon-s-switch-vertical")
                ->action(function ($data) {                   

                    Transaction::create([
                        'transaction_id' => str()->uuid(),
                        'account_id' => $this->record->id,
                        'description' => $data['description'],
                        'type' => $data['type'],
                        'amount' => $data['amount'] * 100,
                    ]);                  

                    $this->emit('refreshTable');
                })
                ->form([

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
                        ->label(function () {
                            return 'Transaction Amount ' . '(current-balance: ' . 'R' . number_format($this->record->balance / 100, 2) . ')';
                        })
                        ->integer()
                        ->reactive()
                        ->minValue(1)
                        ->rules([

                            function (Closure $get) {
                                    $type = $get('type');                             

                                    if($type == 'debit'){

                                        $balance = $this->record->balance / 100;

                                        return "max:{$balance}";
                                        
                                    }
                                    else{
                                        return null;
                                    }                             
                            }


                        ])
                        ->prefix('R')
                        ->mask(
                            fn (Mask $mask) => $mask
                                ->numeric()
                                ->decimalPlaces(2) // Set the number of digits after the decimal point.
                                ->decimalSeparator('.') // Add a separator for decimal numbers.                                             
                                ->minValue(1) // Set the minimum value that the number can be.                     
                        )
                        ->required(),





                ]),




        ];
    }
}
