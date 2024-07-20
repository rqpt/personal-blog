<?php

namespace App\Console\Commands;

use App\Actions\Console\ComposePostMarkdown as ComposePostMarkdown;
use App\Enums\PostStatus;
use App\Enums\TextEditor;
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
                placeholder: 'E.g. Are humans able to live off of tubby custard?',
                validate: ['postTitle' => ['required', 'unique:posts,title']],
                name: 'title',
            )
            ->select(
                TextEditor::selectLabel(),
                TextEditor::selectOptions(),
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
            $post->update(['status' => PostStatus::PUBLISHED]);

            $url = $post->getUrl();

            outro("We've successfully published the post! 🍾");
            outro("You can access it at $url");
        }
    }
}
