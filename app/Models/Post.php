<?php

namespace App\Models;

use Illuminate\Support\Str;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\{Builder, Model};

class Post extends Model
{
    protected $casts = [
        'published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $post->html = Markdown::convert($post->markdown)->getContent();
        });
    }

    public function resolveRouteBinding($value, $field = null): Model|null
    {
        $postId = last(explode('-', $value));

        return parent::resolveRouteBinding($postId, $field);
    }

    // Scopes

    public function scopePublished(Builder $query): void
    {
        $query->where('published', true)
            ->orderBy('updated_at', 'desc')
            ->select(['id', 'title']);
    }

    // Helpers

    public function getUrl(): string
    {
        return url($this->getUrlSlug());
    }

    public function getUrlSlug(): string
    {
        return $this->asSlug() . '-' . $this->id;
    }

    public function asSlug(?string $title = null): string
    {
        return Str::slug($title ?? $this->title);
    }
}
