<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_quote'];

    protected $table = 'invoice_statuses';

    public function default_converted_status()
    {
        return $this->hasOne(InvoiceBasicInfo::class, 'id', 'default_converted_status');
    }
}
