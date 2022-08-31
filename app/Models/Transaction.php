<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'account_id',
        'description',
        'type',
        'amount',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function statement()
    {
        return $this->hasMany(Statement::class, 'transaction_id');
    }
}
