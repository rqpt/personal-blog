<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\{
    Contracts\Console\PromptsForMissingInput,
    Console\Command,
};

use function Laravel\Prompts\{outro, search};

class DeletePost extends Command implements PromptsForMissingInput
{
    protected $signature = 'post:delete {post}'; // $post->title

    protected $description = 'Delete a post';

    public function handle()
    {
        $postTitle = $this->argument('post');

        $post = Post::where('title', $postTitle)->sole();

        $post->delete();

        outro("Successfully deleted a post");
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
}
