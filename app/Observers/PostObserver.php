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
            author: $frontmatter['author'],
        );

        $post->contains_code = Str::contains($html, '<pre>');
    }

    public function saved(Post $post): void
    {
        $detachedTags = Tag::whereNotIn('name', $post->frontmatter->tags)->get();

        foreach ($detachedTags as $tag) {
            $post->tags()->detach($tag);
        }

        foreach ($post->frontmatter->tags as $name) {
            $tag = Tag::firstOrCreate(compact('name'));

            if (! $post->tags()->find($tag)) {
                $post->tags()->attach($tag);
            }
        }
    }
}
