<?php

namespace App\Models;

use App\Enums\LeaveTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'department_id',
        'from',
        'to',
        'type',
        'attachments',
        'revisioned_by',
        'revisioned_on',
        'status',
        'user_notes',
        'revisioned_notes',
    ];

    protected $casts = [
        'type' => LeaveTypes::class,
        'from' => 'datetime',
        'to' => 'datetime',
        'attachments' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function revisionedBy()
    {
        return $this->belongsTo(User::class, 'revisioned_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
