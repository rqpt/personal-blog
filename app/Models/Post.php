<?php

namespace App\Models;

use App\Enums\PostStatus;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $casts = [
        'status' => PostStatus::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $post->html = Markdown::convert($post->markdown)->getContent();
        });
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $postId = last(explode('-', $value));

        return parent::resolveRouteBinding($postId, $field);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', PostStatus::PUBLISHED)
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

    public function containsCode(): bool
    {
        return Str::contains($this->html, '<pre>');
    }
}
