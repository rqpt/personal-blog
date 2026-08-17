<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use MassPrunable;

    /** @var bool */
    public $timestamps = false;

    /** @return BelongsToMany<\App\Models\Post> */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    /** @return Builder<\App\Models\Tag> */
    public function prunable(): Builder
    {
        return static::doesntHave('posts');
    }
}
