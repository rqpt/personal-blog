<?php

namespace App\Models;

use App\Enums\PostType;
use App\Observers\PostObserver;
use App\ValueObjects\Frontmatter;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[ObservedBy(PostObserver::class)]
class Post extends Model
{
    /** @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $casts = [
        'type' => PostType::class,
        'frontmatter' => Frontmatter::class,
        'contains_code' => 'boolean',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $postId = last(explode('-', $value));

        return parent::resolveRouteBinding($postId, $field);
    }

    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->select(['id', 'title']);
    }

    public function scopePinned(Builder $query): void
    {
        $query->published()->whereType(PostType::PINNED);
    }

    public function scopePromotional(Builder $query): void
    {
        $query->published()->whereType(PostType::PROMOTIONAL);
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
