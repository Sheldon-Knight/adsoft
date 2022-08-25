<?php

namespace App\Observers;

use App\Models\Statement;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class TransferObserver
{
    /**
     * Handle the Transfer "created" event.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return void
     */
    public function created(Transfer $transfer)
    {
        $openingBalanceFromAccount = $transfer->fromAccount->balance;

        $closingBalanceFromAccount = $openingBalanceFromAccount - $transfer->amount;

        $openingBalanceToAccount = $transfer->toaccount->balance;

        $closingBalanceToAccount = $openingBalanceToAccount + $transfer->amount;

        $transfer->fromAccount->balance -= $transfer->amount;

        $transfer->toAccount->balance += $transfer->amount;

        $uuid = str()->uuid();
        
        DB::table('transactions')->insert([
            'transaction_id' => $uuid,
            'account_id' =>  $transfer->fromAccount->id,
            'description' =>  "Transfer made from account:" . $transfer->fromAccount->account_number . " to account:" . $transfer->toAccount->account_number . " for R" . number_format($transfer->amount, 2),
            'type' =>  'debit',
            'amount' =>  $transfer->amount,
            'created_at' =>  now(),
            'updated_at' =>  now(),
        ]);

        $transaction = Transaction::where('transaction_id', $uuid)->first();

        Statement::create([
            'account_id' => $transfer->fromAccount->id,
            'transaction_id' => $transaction->id,
            'description' => $transaction->description,
            'debit' => $transfer->amount,
            'credit' => 0,
            'opening_balance' => $openingBalanceFromAccount,
            'closing_balance' => $closingBalanceFromAccount,
        ]);


        Statement::create([
            'account_id' => $transfer->toAccount->id,
            'transaction_id' => $transaction->id,
            'description' => $transaction->description,
            'debit' => 0,
            'credit' => $transfer->amount,
            'opening_balance' => $openingBalanceToAccount,
            'closing_balance' => $closingBalanceToAccount,
        ]);

        $transfer->transaction_id = $transaction->id;

        $transfer->save();

        $transfer->fromAccount->save();

        $transfer->toAccount->save();
    }

    /**
     * Handle the Transfer "updated" event.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return void
     */
    public function updated(Transfer $transfer)
    {
        //
    }

    /**
     * Handle the Transfer "deleted" event.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return void
     */
    public function deleted(Transfer $transfer)
    {
        //
    }

    /**
     * Handle the Transfer "restored" event.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return void
     */
    public function restored(Transfer $transfer)
    {
        //
    }

    /**
     * Handle the Transfer "force deleted" event.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return void
     */
    public function forceDeleted(Transfer $transfer)
    {
        //
    }
}
