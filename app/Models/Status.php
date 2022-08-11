<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name',];

  
    protected $table = 'status';


    public function invoices()
    {
        return $this->hasMany(Invoice::class,'invoice_status');
    }

    public function onlyInvoices()
    {
        return $this->hasMany(Invoice::class, 'invoice_status')->where('is_quote', false);
    }

    public function onlyQuotes()
    {
        return $this->hasMany(Invoice::class, 'invoice_status')->where('is_quote', true);
    }
}
