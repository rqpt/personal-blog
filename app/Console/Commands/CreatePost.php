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

        $postsPath = 'posts';

        $draftPath = "$postsPath/drafts/{$postTitleSlug}.md";

        $textEditorChoice = select(
            label: 'Which text editor would you prefer for the post body?',
            options: TextEditor::getSelectLabels(),
            scroll: 10,
        );

        if ($textEditorChoice == 'builtin') {
            info("No worries, here's one for you.");

            $body = textarea(
                label: 'Please write your post in markdown format.',
                required: true,
                rows: 25,
            );

            Storage::put($draftPath, $body);
        } else {
            info("You will now enter your editor, and we won't see you again before you return to us with some content.");

            pause('Are you ready to embark on your quest?');

            info('Safe travels!');

            $draftIsSaved = false;
            $draftIsEmpty = true;

            $fullDraftPath = Storage::path($draftPath);

            do {
                Process::forever()->tty()->run(
                    "$textEditorChoice $fullDraftPath",
                );

                $draftIsSaved = Storage::exists($draftPath);
                $draftIsEmpty = $draftIsSaved && Storage::size($draftPath) == 0;
            } while (!$draftIsSaved || $draftIsEmpty);
        }

        Post::create(['title' => $postTitleSlug]);

        outro("Nicely done! You've successfully created a draft post.");
    }
}
