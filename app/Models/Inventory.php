<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'attributes'];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function readings()
    {
        return $this->hasMany(InventoryReadings::class, 'inventory_id');
    }
}
