<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $uniquePostsRequired = 4;

        $apiResponses = Http::pool(function (Pool $pool) use ($uniquePostsRequired) {
            $ocean = [];

            $languages = ['python', 'c', 'rust', 'lua', 'haskell', 'ruby', 'html', 'curl', 'shell'];

            for ($i = 0; $i < $uniquePostsRequired; $i++) {
                $prompt = <<<EOD
                Please write a medium sized $languages[$i] snippet,
                wrapped in markdown fencing,
                with $languages[$i] annotated next to the opening fence.
                Prepend a heading 2 before it, please.";
                EOD;

                $ocean[] = $pool->as("md-$i")->get(config('third-party-api.random_markdown.url'));
                $ocean[] = $pool->as("api-$i")
                    ->withToken(config('third-party-api.openai.api_key'))
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post(config('third-party-api.openai.url'), [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $prompt,
                            ],
                        ],
                    ]);
            }

            return $ocean;
        });

        for ($i = 0; $i < $uniquePostsRequired; $i++) {
            Post::factory(state: [
                'markdown' => $apiResponses["md-$i"].$apiResponses["api-$i"]->json('choices.0.message.content'),
            ])->published()->create();

            Post::factory(state: [
                'markdown' => $apiResponses["md-$i"].$apiResponses["api-$i"]->json('choices.0.message.content'),
            ])->published()->withAnEmbeddedVideo()->create();
        }
    }
}
