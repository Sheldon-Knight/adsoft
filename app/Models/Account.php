<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_number',
        'bank_name',
        'branch',
        'branch_code',
        'balance',
    ];

    public function transfersFrom()
    {
        return $this->hasMany(Transfer::class, 'from_account');
    }

    public function transfersTo()
    {
        return $this->hasMany(Transfer::class, 'to_account');
    }

    public function getFullNameAttribute()
    {
        return $this->account_number .  ':' . 'R' . number_format($this->balance / 100,2);
    }
    
}
