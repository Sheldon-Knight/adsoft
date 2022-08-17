<?php

namespace App\Models;

use App\Contracts\IsComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model implements IsComment
{
    use HasFactory;
    
    use SoftDeletes;

    protected $guarded = [];

    public function commentable(): MorphTo
    {
        return $this->morphTo()->where("parent_id");
    } 

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
