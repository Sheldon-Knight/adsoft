<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJob extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'userjobs';
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }


    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }
  
    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

}
