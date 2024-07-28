<?php

namespace App\Console\Commands;

use App\Actions\Console\ComposePostMarkdown as ComposePostMarkdown;
use App\Models\Post;
use App\PostType;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreatePost extends Command
{
    protected $signature = 'post:create';

    protected $description = 'Create a draft post';

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

        $type = select(
            label: 'What type of post is this?',
            options: PostType::asFormOptions(),
            default: PostType::REGULAR->value,
        );

        $post = Post::create(compact('title', 'type', 'markdown'));

        outro("Nicely done! You've successfully created a draft post.");

        $publishNow = confirm(
            label: 'Would you like to publish the post now?',
            yes: 'Sure, why not?',
            no: 'No, maybe later.',
            default: true,
        );

        if ($publishNow) {
            $post->update(['published_at' => now()]);

            outro("We've successfully published the post! 🍾");
            outro("You can access it at {$post->url()}");
        }
    }
}
