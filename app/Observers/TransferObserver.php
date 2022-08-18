<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Transfer;

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
        $transfer->fromAccount->balance -= $transfer->amount;

        $transfer->toAccount->balance += $transfer->amount;
    
        $transaction = Transaction::create([
            'transaction_id' => str()->uuid(),
            'account_id' =>  $transfer->fromAccount->id,
            'description' =>  'Transfer Made to ' . $transfer->toAccount->account_number,
            'type' =>  'credit',
            'amount' =>  $transfer->amount,
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
