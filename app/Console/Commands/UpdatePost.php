<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\{Storage, Process};
use App\{
    Enums\TextEditor,
    Models\Post,
};
use Illuminate\{
    Contracts\Console\PromptsForMissingInput,
    Console\Command,
};
use Symfony\Component\Console\{
    Output\OutputInterface,
    Input\InputInterface,
};

use function Laravel\Prompts\{form, outro, pause, search, select, text, textarea};

class UpdatePost extends Command implements PromptsForMissingInput
{
    protected $signature = 'post:update
    {post}
    {--e|edit}
    {--r|rename}
    {--p|published}';

    protected $description = 'Update a post';

    public function handle()
    {
        $postTitle = $this->argument('post');

        $post = Post::where('title', $postTitle)->sole();

        $localBackupFilename = $post->getBackupFilename();

        $updateValues = [
            'published' => $this->option('published'),
        ];

        if ($this->option('edit')) {
            $preferredTextEditor = select(
                label: 'Which text editor would you prefer for the post body?',
                options: TextEditor::selectLabels(),
            );

            if ($preferredTextEditor == 'builtin') {
                info("No worries, here's one for you.");

                $body = textarea(
                    label: 'Please write your post in markdown format.',
                    default: Storage::disk('backup')->get($localBackupFilename),
                    required: true,
                    rows: 25,
                );
            } else {
                info("You will now enter your editor, and we won't see you again before you return to us with some content.");

                pause('Are you ready to embark on your quest?');

                info('Safe travels!');

                $draftIsSaved = false;
                $draftIsEmpty = true;

                $fullDraftPath = Storage::disk('backup')
                    ->path($localBackupFilename);

                do {
                    Process::forever()->tty()->run(
                        "$preferredTextEditor $fullDraftPath",
                    );

                    $draftIsSaved = Storage::disk('backup')
                        ->exists($localBackupFilename);

                    $draftIsEmpty = $draftIsSaved
                        && Storage::disk('backup')
                        ->size($localBackupFilename) == 0;
                } while (!$draftIsSaved || $draftIsEmpty);

                $body = Storage::disk('backup')->get($localBackupFilename);
            }

            $updateValues['body'] = $body;

            outro("We've successfully edited a post!");
        }

        if ($this->option('rename')) {
            $newPostTitle = text(
                label: 'What would you like the new post title to be?',
                placeholder: 'E.g. I give myself very good advice, but I very seldom follow it.',
                default: $post->title,
                validate: ['postTitle' => ['required', 'unique:posts,title']],
            );

            $updateValues['title'] = $newPostTitle;

            outro("We've successfully renamed a post!");
        }

        $post->update($updateValues);

        $url = $post->getUrl();

        $publishedState = $post->published ? 'published' : 'drafted';

        outro("We've successfully $publishedState the post! 🍾");

        if ($post->published) {
            outro("You can access it at $url");
        }
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        $titles = Post::select('title')->pluck('title')->all();

        return [
            'post' => fn() => search(
                label: 'Search for a post:',
                options: fn($value) => strlen($value) > 0
                    ? Post::where('title', 'like', "%{$value}%")->pluck('title')->all()
                    : $titles,
                required: true,
            )
        ];
    }

    protected function afterPromptingForMissingArguments(
        InputInterface $input,
        OutputInterface $output,
    ): void {
        $formResponses = form()
            ->confirm(
                label: 'Rename your post?',
                default: $this->option('rename'),
                name: 'rename',
            )
            ->confirm(
                label: 'Edit the body of the post?',
                default: $this->option('edit'),
                name: 'edit',
            )
            ->confirm(
                label: "Change the published/draft status?",
                yes: "Publish",
                no: "Draft",
                name: 'published',
            )
            ->submit();

        foreach ($formResponses as $option => $response) {
            $input->setOption($option, $response);
        }
    }
}
