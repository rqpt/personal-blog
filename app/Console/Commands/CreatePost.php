<?php

namespace App\Console\Commands;

use App\Console\ComposePostMarkdown;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\info;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\text;

class CreatePost extends Command
{
    protected $signature = 'post:create';

    protected $description = 'Create a post';

    public function handle(): void
    {
        info('Welcome to the post creation wizard!');

        $title = text(
            label: 'Please provide a post title.',
            placeholder: 'E.g. I give myself very good advice, but I very seldom follow it.',
            validate: ['postTitle' => ['required', 'unique:posts,title']],
        );

        $bodyTmpFilename = Str::slug($title).'.md';

        $markdown = ComposePostMarkdown::handle(
            $bodyTmpFilename,
        );

        $published_at = now();

        $post = Post::create(
            compact('title', 'markdown', 'published_at'),
        );

        outro("We've successfully published a post! 🍾");
        outro("You can access it at {$post->url()}");
    }
}
