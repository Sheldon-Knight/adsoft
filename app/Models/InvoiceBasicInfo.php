<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceBasicInfo extends Model
{
    use HasFactory;

    protected $table = 'invoice_basic_info_settings';

    protected $guarded = ['id'];
}
