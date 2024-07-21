<?php

namespace App\Console\Commands;

use App\Actions\Console\ComposePostMarkdown as ComposePostMarkdown;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\outro;

class CreatePost extends Command
{
    protected $signature = 'post:create';

    protected $description = 'Create a draft post';

    public function handle(): void
    {
        info('Welcome to the post creation wizard!');

        $formResponses = form()
            ->text(
                label: 'Please provide a post title.',
                placeholder: 'E.g. I give myself very good advice, but I very seldom follow it.',
                validate: ['postTitle' => ['required', 'unique:posts,title']],
                name: 'title',
            )
            ->select(
                label: 'Select your preferred text editor for the post body.',
                options: ['nvim' => 'neovim', 'builtin'],
                default: 'nvim',
                name: 'preferredTextEditor',
            )
            ->submit();

        $title = $formResponses['title'];

        $bodyTmpFilename = Str::slug($title).'.md';

        $markdown = ComposePostMarkdown::handle(
            $formResponses['preferredTextEditor'],
            $bodyTmpFilename,
        );

        $post = Post::create(compact('title', 'markdown'));

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
