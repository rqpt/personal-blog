<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Observers\PostObserver;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\{
    Attributes\ObservedBy,
    Casts\Attribute,
    Builder,
    Model,
};

#[ObservedBy([PostObserver::class])]
class Post extends Model
{
    protected $casts = [
        'published' => 'boolean',
    ];

    protected function body(): Attribute
    {
        return Attribute::set(function (string $markdown) {
            return Markdown::convert($markdown)->getContent();
        });
    }

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

    public function getBackupFilename(?string $originalTitle = null): string
    {
        return $this->asSlug($originalTitle) . '.md';
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('published', true)
            ->orderBy('updated_at', 'desc')
            ->select(['id', 'title']);
    }

    public function resolveRouteBinding($value, $field = null): Model|null
    {
        $postId = last(explode('-', $value));

        return parent::resolveRouteBinding($postId, $field);
    }
}
