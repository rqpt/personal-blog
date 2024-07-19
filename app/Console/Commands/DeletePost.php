<?php

namespace App\Console\Commands;

use App\Models\Post;
use Closure;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

use function Laravel\Prompts\outro;
use function Laravel\Prompts\search;

class DeletePost extends Command implements PromptsForMissingInput
{
    protected $signature = 'post:delete {post}'; // $post->title

    protected $description = 'Delete a post';

    public function handle(): void
    {
        $postTitle = $this->argument('post');

        $post = Post::where('title', $postTitle)->sole();

        $post->delete();

        outro('Successfully deleted a post');
    }

    /** @return array{post: Closure(): (int|string)}  */
    protected function promptForMissingArgumentsUsing(): array
    {
        $titles = Post::select('title')->pluck('title')->all();

        return [
            'post' => fn () => search(
                label: 'Search for a post:',
                options: fn (string $value) => strlen($value) > 0
                    ? Post::where('title', 'like', "%{$value}%")->pluck('title')->all()
                    : $titles,
                required: true,
            ),
        ];
    }
}
