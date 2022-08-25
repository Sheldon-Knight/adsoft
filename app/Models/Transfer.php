<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'from_account',
        'to_account',
        'transaction_id',
        'amount',
    ];
  
    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }   
}
