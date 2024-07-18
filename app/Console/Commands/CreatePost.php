<?php

namespace App\Console\Commands;

use App\Actions\Console\ComposePostBody;
use Illuminate\{
    Support\Str,
    Console\Command,
};
use App\{
    Models\Post,
    Enums\TextEditor,
};

use function Laravel\Prompts\{confirm, form, info, outro};

class CreatePost extends Command
{
    protected $signature = 'post:create';

    protected $description = 'Create a draft post';

    public function handle()
    {
        info('Welcome to the post creation wizard!');

        $formResponses = form()
            ->text(
                label: 'What would you like the post to be titled?',
                placeholder: 'E.g. Are humans able to live off of tubby custard?',
                validate: ['postTitle' => ['required', 'unique:posts,title']],
                name: 'postTitle',
            )
            ->select(
                label: 'Which text editor would you prefer for the post body?',
                options: TextEditor::selectLabels(),
                name: 'preferredTextEditor',
            )
            ->submit();

        $localBackupFilename = Str::slug($formResponses['postTitle']) . '.md';

        ComposePostBody::handle(
            $formResponses['preferredTextEditor'],
            $localBackupFilename,
        );

        $post = Post::create([
            'title' => $formResponses['postTitle'],
        ]);

        outro("Nicely done! You've successfully created a draft post.");

        $publishNow = confirm(
            label: "Would you like to publish the post now?",
            yes: "Sure, why not?",
            no: "No, maybe later.",
            default: false,
        );

        if ($publishNow) {
            $post->update(['published' => true]);

            $url = $post->getUrl();

            outro("We've successfully published the post! 🍾");
            outro("You can access it at $url");
        }
    }
}
