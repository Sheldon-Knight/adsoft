<?php

namespace App\Models;

use App\Concerns\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instruction extends Model
{
    use HasFactory, HasComments,SoftDeletes;

    protected $fillable = [
        'title',
        'instruction',
        'due_date',
        'date_completed',
        'status',
        'created_by',
        'assigned_to',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
