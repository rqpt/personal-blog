<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\{
    Attributes\ObservedBy,
    Builder,
    Model,
};

#[ObservedBy([PostObserver::class])]
class Post extends Model
{
    protected $casts = [
        'published' => 'boolean',
    ];

    public function prettyTitle(): string
    {
        return Str::of($this->title)->replace('-', ' ')->title();
    }

    public function urlSlug(): string
    {
        return $this->title . '-' . $this->id;
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('published', true)->select(['id', 'title']);
    }

    public function resolveRouteBinding($value, $field = null): Model|null
    {
        $id = last(explode('-', $value));

        return parent::resolveRouteBinding($id, $field);
    }
}
