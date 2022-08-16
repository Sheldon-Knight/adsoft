<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'postal_address',
        'physical_address',
        'vat_number',
        'client_name',
        'client_surname',
        'tel_num',
        'cell_num',
        'fax_num',
        'contact_person',
        'reg_type',
        'reg_number',
        'account_name',
        'account_number',
        'account_type',
        'branch_code',
        'bank_name',
        'branch_name',
        'email',
        'client_status',
    ];


    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id')->where('is_quote',false);
    }

    public function quotes()
    {
        return $this->hasMany(Invoice::class, 'client_id')->where('is_quote', true);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'client_id');
    }
}
