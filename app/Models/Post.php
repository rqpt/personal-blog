<?php

namespace App\Models;

use App\Enums\PostType;
use App\Observers\PostObserver;
use App\ValueObjects\Frontmatter;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
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
        'contains_video' => 'boolean',
    ];

    /** @return BelongsToMany<\App\Models\Tag> */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function booted(): void
    {
        /** @param Builder<\App\Models\Post> $builder */
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->whereNotNull('published_at')
                ->orderBy('updated_at', 'desc')
                ->limit(3);
        });
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $postId = last(explode('-', $value));

        return parent::resolveRouteBinding($postId, $field);
    }

    public function publishedAt(): Attribute
    {
        return $this->formatTimestamp();
    }

    public function updatedAt(): Attribute
    {
        return $this->formatTimestamp();
    }

    /** @param Builder<\App\Models\Post> $query  */
    public function scopeSurfaceInfo(Builder $query): void
    {
        $query->select(['id', 'title']);
    }

    /** @param Builder<\App\Models\Post> $query */
    public function scopePinned(Builder $query): void
    {
        $query->surfaceInfo()->whereType(PostType::PINNED);
    }

    /** @param Builder<\App\Models\Post>  $query */
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

    private function formatTimestamp(): Attribute
    {
        return Attribute::make(
            get: function (string $timestamp): string {
                return Carbon::parse($timestamp)->format('d-M-Y');
            }
        );
    }
}
