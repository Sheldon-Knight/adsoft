<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Account;
use App\Models\Statement;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $transaction = static::getModel()::create($data);

        $account = Account::find($data['account_id']);

        $statement = Statement::create([
            'account_id' => $account->id,
            'transaction_id' => $transaction->id,
            'description' => $transaction->description,
            'debit' => $transaction->type === 'debit' ? $account->balance : 0,
            'credit' => $transaction->type === 'credit' ? $account->balance : 0,
            'opening_balance' => $account->balance,
            'closing_balance' => $data['type'] == 'credit' ? $account->balance + $data['amount'] : $account->balance - $data['amount'],
        ]);

        $statement->update([
            'closing_balance' => $account->balance,
        ]);

        return $transaction;
    }
}
