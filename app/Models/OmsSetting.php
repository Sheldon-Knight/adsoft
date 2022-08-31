<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;

class OmsSetting extends Model
{
    use HasFactory, HasSubscriptions;

    protected $table = 'oms_settings';

    protected $fillable = [
        'oms_name',
        'oms_company_name',
        'oms_email',
        'oms_company_tel',
        'oms_company_address',
        'oms_company_vat',
        'oms_company_registration',
        'oms_logo',
        'date_format',
        'invoice_series',
        'quote_series',
        'invoice_notes',
        'quote_notes',
    ];

    public function hasExpired()
    {
        return empty($this->subscription);
    }
}
