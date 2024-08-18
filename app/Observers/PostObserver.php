<?php

namespace App\Observers;

use App\Enums\PostFeature;
use App\Exceptions\FrontmatterMissingException;
use App\Models\Post;
use App\Models\Tag;
use App\ValueObjects\Frontmatter;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Str;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class PostObserver
{
    public function saving(Post $post): void
    {
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
            author: $frontmatter['author'] ?? 'PE Vermeulen',
        );

        $features = [];

        if (Str::contains($html, 'lite-youtube')) {
            $features[] = PostFeature::VIDEO;
        }

        if (Str::contains($html, '<pre>')) {
            $features[] = PostFeature::CODE;
        }

        $post->features = $features;
    }

    public function saved(Post $post): void
    {
        foreach ($post->frontmatter->tags as $name) {
            $post->tags()->firstOrCreate(compact('name'));
        }

        if (! $post->wasChanged('frontmatter')) {
            return;
        }

        $originalFrontmatter = $post->getOriginal('frontmatter');

        $originalTags = $originalFrontmatter->tags;

        $tagsToDetach = array_diff($originalTags, $post->frontmatter->tags);

        $tagsToDetach = Tag::where('name', $tagsToDetach)->get();

        foreach ($tagsToDetach as $tag) {
            $post->tags()->detach($tag);
        }
    }
}
