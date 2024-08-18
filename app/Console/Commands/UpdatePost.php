<?php

namespace App\Console\Commands;

use App\Console\ComposePostMarkdown;
use App\Enums\PostType;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\form;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class UpdatePost extends Command implements PromptsForMissingInput
{
    protected $signature = 'post:update
    {post}
    {--e|edit}
    {--t|type}
    {--r|rename}
    {--p|published}';

    protected $description = 'Update a post';

    public function handle(): void
    {
        $postTitle = $this->argument('post');

        $post = Post::withoutGlobalScopes()->where('title', $postTitle)->sole();

        $bodyTmpFilename = Str::slug($post->title).'.md';

        if ($this->option('published')) {
            $updateValues = [
                'published_at' => $post->published_at ??= now(),
            ];
        } else {
            $updateValues = [
                'published_at' => null,
            ];
        }

        if ($this->option('edit')) {
            $updateValues['markdown'] = ComposePostMarkdown::handle(
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

        if ($this->option('type')) {
            $typeSelect = select(
                label: 'What type of post is this?',
                options: PostType::asFormOptions(),
                default: PostType::REGULAR->asString(),
            );

            $newPostType = PostType::from((string) $typeSelect);

            $updateValues['type'] = $newPostType;

            outro("We've successfully changed the post's type!");
        }

        $post->update($updateValues);

        if ($post->published_at) {
            outro("You can access it at {$post->url()}");
        }
    }

    /** @return array<string, mixed>  */
    protected function promptForMissingArgumentsUsing(): array
    {
        $titles = Post::withoutGlobalScopes()->select('title')->get()->pluck('title')->toArray();

        return [
            'post' => fn () => search(
                label: 'Search for a post:',
                options: fn (string $value): array => strlen($value) > 0
                    ? Post::withoutGlobalScopes()->where('title', 'like', "%{$value}%")->pluck('title')->toArray()
                    : $titles,
                required: true,
            ),
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
            )->confirm(
                label: "Modify the post's type?",
                default: false,
                name: 'type',
            )
            ->confirm(
                label: 'Change the published/draft status?',
                yes: 'Publish',
                no: 'Draft',
                name: 'published',
            )
            ->submit();

        foreach ($formResponses as $option => $response) {
            $input->setOption($option, $response);
        }
    }
}
