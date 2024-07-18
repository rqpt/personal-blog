<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\{Storage, Process};
use Illuminate\{
    Support\Str,
    Console\Command,
};
use App\{
    Models\Post,
    Enums\TextEditor,
};

use function Laravel\Prompts\{confirm, form, textarea, info, pause, outro};

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

        if ($formResponses['preferredTextEditor'] == 'builtin') {
            info("No worries, here's one for you.");

            $body = textarea(
                label: 'Please write your post in markdown format.',
                required: true,
                rows: 25,
            );

            Storage::disk('backup')->put($localBackupFilename, $body);
        } else {
            info("You will now enter your editor, and we won't see you again before you return to us with some content.");

            pause('Are you ready to embark on your quest?');

            info('Safe travels!');

            $localBackupIsSaved = false;
            $localBackupIsEmpty = true;

            $fullDraftPath = Storage::disk('backup')
                ->path($localBackupFilename);

            do {
                Process::forever()->tty()->run(
                    "{$formResponses['preferredTextEditor']} $fullDraftPath",
                );

                $localBackupIsSaved = Storage::disk('backup')
                    ->exists($localBackupFilename);

                $localBackupIsEmpty = $localBackupIsSaved
                    && Storage::disk('backup')
                    ->size($localBackupFilename) == 0;
            } while (!$localBackupIsSaved || $localBackupIsEmpty);
        }

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
