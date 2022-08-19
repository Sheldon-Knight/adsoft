<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Statement;
use App\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction)
    {

        $account = Account::find($transaction->account_id);



        $openingBalance =  $account->balance;
        $closingBalance =  $transaction->type === 'debit' ? $openingBalance - $transaction->amount : $openingBalance + $transaction->amount;

        $account->update([
            'balance' => $transaction->type === 'debit' ? $account->balance - $transaction->amount : $account->balance + $transaction->amount,
        ]);   

        Statement::create([
            'account_id' => $account->id,
            'transaction_id' => $transaction->id,
            'description' => $transaction->description,
            'debit' =>  $transaction->type === 'debit' ? $transaction->amount : 0,
            'credit' => $transaction->type === 'credit' ? $transaction->amount : 0,
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
        ]);

    }
}
