<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory, MassPrunable;

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function prunable(): Builder
    {
        return static::doesntHave('posts');
    }
}
