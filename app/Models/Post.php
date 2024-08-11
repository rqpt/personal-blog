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
        'contains_video' => 'boolean',
        'created_at' => 'datetime:d-M-Y',
        'updated_at' => 'datetime:d-M-Y',
        'published_at' => 'datetime:d-M-Y',
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

    /** @param Builder<\App\Models\Post> $query */
    public function scopeRegular(Builder $query): void
    {
        $query->whereType(PostType::REGULAR);
    }

    /** @param Builder<\App\Models\Post> $query */
    public function scopePinned(Builder $query): void
    {
        $query->whereType(PostType::PINNED);
    }

    /** @param Builder<\App\Models\Post>  $query */
    public function scopePromotional(Builder $query): void
    {
        $query->whereType(PostType::PROMOTIONAL);
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

    public function timestamps(): string
    {
        return $this->simplifyTimestampInfo(
            "󰚧  $this->published_at",
            "󰚰  $this->updated_at",
        );
    }

    public function timestampTooltip(): string
    {
        return $this->simplifyTimestampInfo(
            'Published at',
            'Updated at',
        );
    }

    private function simplifyTimestampInfo(string $final, string $optional): string
    {
        if ($this->published_at != $this->updated_at) {
            $final .= " | $optional";
        }

        return $final;
    }
}
