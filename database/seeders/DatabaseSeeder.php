<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\{
    Support\Facades\Http,
    Http\Client\Pool,
    Database\Seeder,
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $bodiesRequired = 3;

        $markdownResponses = Http::pool(fn(Pool $pool) => [
            $pool->get(config('third-party-api.random_markdown')),
            $pool->get(config('third-party-api.random_markdown')),
            $pool->get(config('third-party-api.random_markdown')),
        ]);

        Post::factory($bodiesRequired, [
            'markdown' => $markdownResponses[0],
        ])->published()->create();

        Post::factory($bodiesRequired, [
            'markdown' => $markdownResponses[1],
        ])->published()->withTableOfContents()->create();

        Post::factory($bodiesRequired, [
            'markdown' => $markdownResponses[2],
        ])->published()->withTableOfContents()->withAnEmbeddedVideo()->create();
    }
}
