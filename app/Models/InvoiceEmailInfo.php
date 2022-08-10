<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class InvoiceEmailInfo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'invoice_email_info_settings';

    protected $guarded = ['id'];
}
