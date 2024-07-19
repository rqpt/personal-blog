<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Support\Str;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
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

    public function resolveRouteBinding($value, $field = null): Model|null
    {
        $postId = last(explode('-', $value));

        return parent::resolveRouteBinding($postId, $field);
    }

    // Scopes

    public function scopePublished(Builder $query): void
    {
        $query->where('status', PostStatus::PUBLISHED)
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
