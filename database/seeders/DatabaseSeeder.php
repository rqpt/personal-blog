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
        $uniquePostsRequired = 2;

        $markdownResponses = Http::pool(function (Pool $pool) use ($uniquePostsRequired) {
            $ocean = [];

            for ($i = 0; $i < $uniquePostsRequired; $i++) {
                $ocean[] = $pool->get(config('third-party-api.random_markdown'));
            }

            return $ocean;
        });

        for ($i = 0; $i < $uniquePostsRequired; $i++) {
            Post::factory(state: [
                'markdown' => $markdownResponses[$i],
            ])->published()->create();

            Post::factory(state: [
                'markdown' => $markdownResponses[$i],
            ])->published()->withAnEmbeddedVideo()->create();
        }
    }
}
