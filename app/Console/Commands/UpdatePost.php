<?php

namespace App\Console\Commands;

use App\{
    Actions\Console\ComposePostMarkdown,
    Enums\TextEditor,
    Models\Post,
};
use App\Enums\PostStatus;
use Illuminate\{
    Contracts\Console\PromptsForMissingInput,
    Console\Command,
};
use Illuminate\Support\Str;
use Symfony\Component\Console\{
    Output\OutputInterface,
    Input\InputInterface,
};

use function Laravel\Prompts\{form, outro, search, select, text};

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

        $bodyTmpFilename = Str::slug($post->title) . '.md';

        $updateValues = [
            'status' => $this->option('published')
                ? PostStatus::PUBLISHED
                : PostStatus::DRAFT,
        ];

        if ($this->option('edit')) {
            $preferredTextEditor = select(
                label: 'Which text editor would you prefer for the post body?',
                options: TextEditor::selectLabels(),
            );

            $updateValues['markdown'] = ComposePostMarkdown::handle(
                $preferredTextEditor,
                $bodyTmpFilename,
                $post->markdown,
            );

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

        $status = $post->status == PostStatus::PUBLISHED
            ? 'published'
            : 'drafted';

        outro("We've successfully $status the post! 🍾");

        if ($post->status == PostStatus::PUBLISHED) {
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
