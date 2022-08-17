<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface IsComment
{
    public function commentable(): MorphTo;

    public function replies(): HasMany;

    public function user(): BelongsTo;
}
