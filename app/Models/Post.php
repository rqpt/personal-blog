<?php

namespace App\Models;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $casts = [
        'contains_code' => 'boolean',
        'contains_toc' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $html = Markdown::convert($post->markdown)->getContent();

            $post->html = $html;
            $post->contains_code = Str::contains($html, '<pre>');
            $post->contains_toc = Str::contains($html, '<ul id="toc" ');
        });
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $postId = last(explode('-', $value));

        return parent::resolveRouteBindingQuery($this, $postId, $field)
            ->with('comments')
            ->first();
    }

    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')
            ->orderBy('updated_at', 'desc')
            ->select(['id', 'title']);
    }

    public function url(): string
    {
        return url($this->urlSlug());
    }

    public function urlSlug(): string
    {
        return $this->slug().'-'.$this->id;
    }

    public function slug(?string $title = null): string
    {
        return Str::slug($title ?? $this->title);
    }
}
