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
    use HasFactory;

    protected array $casts = [
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
        return $this->asSlug().'-'.$this->id;
    }

    public function asSlug(?string $title = null): string
    {
        return Str::slug($title ?? $this->title);
    }
}
