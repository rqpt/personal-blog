<?php

namespace App\Console\Commands;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\{Storage, Process};
use Illuminate\Support\Str;
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

        $postsPath = 'posts';

        $draftPath = "$postsPath/drafts/{$postTitleSlug}.md";
        $publishedPath = "$postsPath/published/{$postTitleSlug}.html";

        $updateValues = [];

        $updateValues['published'] = $this->option('published');

        if ($this->option('rename')) {
            $newPostTitle = text(
                label: 'What would you like the new post title to be?',
                placeholder: 'E.g. I give myself very good advice, but I very seldom follow it.',
                default: $postTitleSlug,
                validate: ['postTitle' => ['required', 'unique:posts,title']],
            );

            $postTitleSlug = Str::slug($newPostTitle);

            $newDraftPath = "$postsPath/drafts/{$postTitleSlug}.md";
            $newPublishedPath = "$postsPath/published/{$postTitleSlug}.html";

            if (Storage::exists($draftPath)) {
                Storage::move($draftPath, $newDraftPath);
            }

            if (Storage::exists($publishedPath)) {
                Storage::move($publishedPath, $newPublishedPath);
            }

            $draftPath = $newDraftPath;
            $publishedPath = $newPublishedPath;

            $updateValues['title'] = $postTitleSlug;

            outro("We've successfully renamed a post!");
        }

        $post->update($updateValues);

        if ($this->option('edit')) {
            $textEditorChoice = select(
                label: 'Which text editor would you prefer for the post body?',
                options: TextEditor::getSelectLabels(),
                scroll: 6,
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

            outro("We've successfully edited a post!");
        }

        if ($this->option("published")) {
            $markdown = Storage::get($draftPath);

            $html = Markdown::convert($markdown)->getContent();

            Storage::put($publishedPath, $html);

            $url = url($post->title);

            outro("We've successfully published new post! 🍾");
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
            label: "Would you like to change it's published status?",
            default: false,
        ));
    }
}
