<?php

namespace App\Models;

use App\Concerns\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory, HasComments;
    

    protected $fillable =   [
        'title',
        'description',
        'date_completed',
        'client_id',
        'user_id',
        'department_id',
        'invoice_id',
        'status_id',
        'created_by',
    ];

    protected $table = 'jobs';

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->where('is_quote', false);
    }

    public function quote()
    {
        return $this->belongsTo(Invoice::class)->where('is_quote', true);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
