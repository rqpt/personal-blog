<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PublishedScope implements Scope
{
    /** @param Builder<\App\Models\Post>  */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereNotNull('published_at')
            ->orderBy('updated_at', 'desc')
            ->limit(3);
    }
}
