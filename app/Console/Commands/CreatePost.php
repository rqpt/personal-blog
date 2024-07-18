<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\{
    Storage,
    Process
};
use Illuminate\{
    Support\Str,
    Console\Command,
};
use App\{
    Models\Post,
    Enums\TextEditor,
};

use function Laravel\Prompts\{textarea, text, select, info, pause, outro};

class CreatePost extends Command
{
    protected $signature = 'post:create';

    protected $description = 'Create a draft post';

    public function handle()
    {
        info('Welcome to the post creation wizard!');

        $postTitle = text(
            label: 'What would you like the post to be titled?',
            placeholder: 'E.g. Are humans able to live off of tubby custard?',
            validate: ['postTitle' => ['required', 'unique:posts,title']],
        );

        $postTitleSlug = Str::slug($postTitle);

        $draftFile = "{$postTitleSlug}.md";

        $textEditorChoice = select(
            label: 'Which text editor would you prefer for the post body?',
            options: TextEditor::getSelectLabels(),
        );

        if ($textEditorChoice == 'builtin') {
            info("No worries, here's one for you.");

            $body = textarea(
                label: 'Please write your post in markdown format.',
                required: true,
                rows: 25,
            );

            Storage::disk('drafts')->put($draftFile, $body);
        } else {
            info("You will now enter your editor, and we won't see you again before you return to us with some content.");

            pause('Are you ready to embark on your quest?');

            info('Safe travels!');

            $draftIsSaved = false;
            $draftIsEmpty = true;

            $fullDraftPath = Storage::disk('drafts')->path($draftFile);

            do {
                Process::forever()->tty()->run(
                    "$textEditorChoice $fullDraftPath",
                );

                $draftIsSaved = Storage::disk('drafts')->exists($draftFile);
                $draftIsEmpty = $draftIsSaved && Storage::disk('drafts')->size($draftFile) == 0;
            } while (!$draftIsSaved || $draftIsEmpty);
        }

        Post::create(['title' => $postTitleSlug]);

        outro("Nicely done! You've successfully created a draft post.");
    }
}
