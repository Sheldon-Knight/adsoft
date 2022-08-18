<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Statement;
use App\Models\Transaction;

class AccountObserver
{
    public function created(Account $account)
    {
        $transaction = Transaction::create([
            'transaction_id' => str()->uuid(),
            'account_id' => $account->id,
            'description' => 'New Account Created',
            'type' => 'debit',
            'amount' => $account->balance,
        ]);

        Statement::create([
            'account_id' => $account->id,
            'transaction_id' => $transaction->id,
            'description' => $transaction->description,         
            'debit' => $transaction->type === 'debit' ? $account->balance : 0,
            'credit' => $transaction->type === 'credit' ? $account->balance : 0,
            'opening_balance' => $account->balance,
            'closing_balance' => $account->balance,
        ]);
    }
}
