<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Statement;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class AccountObserver
{
    public function created(Account $account)
    {
        $transactionId = str()->uuid();

        DB::table('transactions')->insert([
            'transaction_id' => $transactionId,
            'account_id' => $account->id,
            'description' => 'New Account Created',
            'type' => 'credit',
            'amount' => $account->balance,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $transaction = Transaction::where('transaction_id', $transactionId)->first();

        Statement::create([
            'account_id' => $account->id,
            'transaction_id' => $transaction->id,
            'description' => $transaction->description,
            'debit' => $transaction->type === 'debit' ? $transaction->amount : 0,
            'credit' => $transaction->type === 'credit' ? $transaction->amount : 0,
            'opening_balance' => $account->balance,
            'closing_balance' => $account->balance,
        ]);
    }

    public function deleted(Account $account)
    {
        Statement::where('account_id', $account->id)->delete();

        Transaction::where('account_id', $account->id)->delete();

        Transfer::where('from_account', $account->id)->delete();
    }

    public function restored(Account $account)
    {
        Statement::withTrashed()->where('account_id', $account->id)->restore();

        Transaction::withTrashed()->where('account_id', $account->id)->restore();

        Transfer::withTrashed()->where('from_account', $account->id)->restore();
    }
}
