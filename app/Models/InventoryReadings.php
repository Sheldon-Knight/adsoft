<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReadings extends Model
{
    use HasFactory;

    protected $table = 'inventory-readings';

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    protected $fillable = [
        'inventory_id',
        'date',
        'start_readings',
        'end_readings',
    ];

    protected $casts = [
        'date' => 'datetime',
        'start_readings' => 'array',
        'end_readings' => 'array',
    ];
}
