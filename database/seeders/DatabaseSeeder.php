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

        $markdownResponses = Http::pool(function (Pool $pool) use ($bodiesRequired) {
            $ocean = [];

            for ($i = 0; $i < $bodiesRequired; $i++) {
                $ocean[] = $pool->get(config('third-party-api.random_markdown'));
            }

            return $ocean;
        });

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
