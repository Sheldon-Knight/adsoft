<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $fillable = [
        'user_id',
        'present',
        'day',
        'time_in',
        'time_out',
    ];

   public function user()
    {
        return $this->belongsTo(User::class);
    }



}
