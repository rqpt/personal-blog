<?php

namespace App\Observers;

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

        $post->contains_video = Str::contains($html, 'embedded-youtube-iframe');

        $post->contains_code = Str::contains($html, '<pre>');
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
