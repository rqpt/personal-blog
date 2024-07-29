<?php

namespace App\Models;

use App\Enums\PostType;
use App\Exceptions\FrontmatterMissingException;
use App\ValueObjects\Frontmatter;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class Post extends Model
{
    /** @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $casts = [
        'type' => PostType::class,
        'frontmatter' => Frontmatter::class,
        'contains_code' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $convertedMarkdown = Markdown::convert($post->markdown);

            if ($convertedMarkdown instanceof RenderedContentWithFrontMatter) {
                $frontmatter = $convertedMarkdown->getFrontMatter();
            } else {
                throw new FrontmatterMissingException;
            }

            $html = $convertedMarkdown->getContent();

            $post->html = $html;
            $post->frontmatter = new Frontmatter(
                title: $frontmatter['title'] ?? $post->title,
                description: $frontmatter['description'],
                tags: $frontmatter['tags'],
                author: $frontmatter['author'],
            );

            $post->contains_code = Str::contains($html, '<pre>');
        });
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
