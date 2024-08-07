<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $uniquePostsRequired = 4;

        $apiResponses = Http::pool(function (Pool $pool) use ($uniquePostsRequired) {
            $ocean = [];

            $snippetLanguages = [
                'python', 'c', 'rust', 'lua', 'haskell', 'ruby', 'alpine',
                'css', 'julia', 'ocaml', 'go', 'elixir', 'cpp', 'dockerfile',
            ];

            $humanLanguages = [
                'english', 'afrikaans',
            ];

            for ($i = 0; $i < $uniquePostsRequired; $i++) {
                $humanLanguage = Arr::random($humanLanguages);
                $snippetLanguage = Arr::random($snippetLanguages);

                $titlePrompt = <<<EOD
                Write a nice title for my blog post, keep it short and sweet.
                The language the title should be written in is $humanLanguage.
                EOD;

                $bodyPrompt = <<<EOD
                Please write a medium sized blog post in markdown. It should have
                headings, tables, ordered and unordered lists, bolded parts,
                italicised parts , maybe even both bolded and italicised sometimes,
                quotes. Line breaks, links, etc. I want to see all markdown has to
                offer. The blog post should be written in $humanLanguage.
                EOD;

                $snippetPrompt = <<<EOD
                Please write a medium sized $snippetLanguage snippet,
                wrapped in markdown fencing, with $snippetLanguage annotated next
                to the opening fence. Prepend a heading 2 before it,
                please. Next to some of the code lines, I want you to add
                some special annotations. I want one line appended with a
                '[tl! ~~]', one with '[tl! ++]', and one with '[tl! --]'. They
                should be wrapped in a comment syntax.
                EOD;

                $ocean[] = $pool->as("title-$i")
                    ->withToken(config('third-party-api.openai.api_key'))
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post(config('third-party-api.openai.url'), [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $titlePrompt,
                            ],
                        ],
                    ]);

                $ocean[] = $pool->as("body-$i")
                    ->withToken(config('third-party-api.openai.api_key'))
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post(config('third-party-api.openai.url'), [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $bodyPrompt,
                            ],
                        ],
                    ]);
                $ocean[] = $pool->as("snippet-$i")
                    ->withToken(config('third-party-api.openai.api_key'))
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post(config('third-party-api.openai.url'), [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $snippetPrompt,
                            ],
                        ],
                    ]);
            }

            return $ocean;
        });

        for ($i = 0; $i < $uniquePostsRequired; $i++) {
            $body = $apiResponses["body-$i"]->json('choices.0.message.content');
            $snippet = $apiResponses["snippet-$i"]->json('choices.0.message.content');

            $title = $apiResponses["title-$i"]->json('choices.0.message.content');
            $markdown = $body."\n\n".$snippet;

            Post::factory(compact('title', 'markdown'))->withVideo()->create();
        }
    }
}
