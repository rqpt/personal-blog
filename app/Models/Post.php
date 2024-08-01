<?php

namespace App\Models;

use App\Enums\PostType;
use App\Models\Scopes\PublishedScope;
use App\Observers\PostObserver;
use App\ValueObjects\Frontmatter;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[ObservedBy(PostObserver::class)]
#[ScopedBy(PublishedScope::class)]
class Post extends Model
{
    /** @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $casts = [
        'type' => PostType::class,
        'frontmatter' => Frontmatter::class,
        'contains_code' => 'boolean',
    ];

    /** @return BelongsToMany<\App\Models\Tag> */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $postId = last(explode('-', $value));

        return parent::resolveRouteBinding($postId, $field);
    }

    /** @param Builder<\App\Models\Post>  */
    public function scopeSurfaceInfo(Builder $query): void
    {
        $query->select(['id', 'name']);
    }

    /** @param Builder<\App\Models\Post>  */
    public function scopePinned(Builder $query): void
    {
        $query->surfaceInfo()->whereType(PostType::PINNED);
    }

    /** @param Builder<\App\Models\Post>  */
    public function scopePromotional(Builder $query): void
    {
        $query->surfaceInfo()->whereType(PostType::PROMOTIONAL);
    }

    public function url(): string
    {
        return url($this->urlSlug());
    }

    public function urlSlug(): string
    {
        return $this->slug().'-'.$this->id;
    }

    public function slug(): string
    {
        return Str::slug($this->title);
    }
}
