<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'invoice_date',
        'invoice_number',
        'invoice_due_date',
        'invoice_status',
        'invoice_total',
        'invoice_subtotal',
        'invoice_tax',
        'invoice_discount',
        'items',
        'user_id',
        'client_id',
        'is_quote',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'items' => 'json'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'invoice_status');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'invoice_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }   
}
