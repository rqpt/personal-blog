<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\{Storage, Process};
use Illuminate\Support\Str;
use App\{
    Enums\TextEditor,
    Models\Post,
};
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\{
    Contracts\Console\PromptsForMissingInput,
    Console\Command,
};
use Symfony\Component\Console\{
    Output\OutputInterface,
    Input\InputInterface,
};

use function Laravel\Prompts\{confirm, outro, pause, search, select, text, textarea};

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

        $postTitleSlug = $post->title;
        $draftFile = "{$postTitleSlug}.md";
        $publishedFile = "{$postTitleSlug}.html";

        if ($this->option('edit')) {
            $textEditorChoice = select(
                label: 'Which text editor would you prefer for the post body?',
                options: TextEditor::getSelectLabels(),
                scroll: 10,
            );

            if ($textEditorChoice == 'builtin') {
                info("No worries, here's one for you.");

                $body = textarea(
                    label: 'Please write your post in markdown format.',
                    default: Storage::disk('drafts')->get($draftFile),
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

            if ($post->published) {
                $markdown = Storage::disk('drafts')->get($draftFile);
                $html = Markdown::convert($markdown)->getContent();

                Storage::disk('published')->put($publishedFile, $html);
            }

            outro("We've successfully edited a post!");
        }

        $updateValues = [
            'published' => $this->option('published'),
        ];

        if ($this->option('rename')) {
            $newPostTitle = text(
                label: 'What would you like the new post title to be?',
                placeholder: 'E.g. I give myself very good advice, but I very seldom follow it.',
                default: $postTitleSlug,
                validate: ['postTitle' => ['required', 'unique:posts,title']],
            );

            $postTitleSlug = Str::slug($newPostTitle);

            $updateValues['title'] = $postTitleSlug;

            outro("We've successfully renamed a post!");
        }

        $post->update($updateValues);

        $url = url($post->title);

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
        $input->setOption('rename', confirm(
            label: 'Would you like to rename your post?',
            default: $this->option('rename')
        ));

        $input->setOption('edit', confirm(
            label: 'Would you like to edit the body of the post?',
            default: $this->option('edit')
        ));

        $input->setOption('published', confirm(
            label: "Change it's published status?",
            yes: "Publish",
            no: "Draft",
            default: false,
        ));
    }
}
