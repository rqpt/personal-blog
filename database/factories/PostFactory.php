<?php

namespace Database\Factories;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

/** @extends Factory<\App\Models\Post> */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'type' => fake()->randomElement(PostType::cases()),
            'markdown' => $this->getFrontMatter()."\n\n".fake()->paragraphs(asText: true),
            'published_at' => now(),
        ];
    }

    /** @return Factory<\App\Models\Post>  */
    public function regular(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'type' => PostType::LATEST,
        ]);
    }

    /** @return Factory<\App\Models\Post>  */
    public function promotional(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'type' => PostType::PROMOTIONAL,
        ]);
    }

    /** @return Factory<\App\Models\Post>  */
    public function pinned(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'type' => PostType::PINNED,
        ]);
    }

    /** @return Factory<\App\Models\Post>  */
    public function withBody(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'markdown' => $this->getFrontMatter()."\n\n".Http::getRandomMarkdown(),
        ]);
    }

    /** @return Factory<\App\Models\Post>  */
    public function withRealisticBody(string $language): Factory
    {
        return $this->state(function (array $attributes) use ($language) {
            $prompt = <<<EOD
            Please write a medium sized blog post in markdown. It should have
            headings, tables, ordered and unordered lists, bolded parts,
            italicised parts , maybe even both bolded and italicised sometimes,
            quotes. Line breaks, links, etc. I want to see all markdown has to
            offer. The blog post should be written in $language.
            EOD;

            $aiResponse = Http::withToken(config('third-party-api.openai.api_key'))
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

            return [
                'markdown' => $this->getFrontMatter()."\n\n".$aiResponse->json('choices.0.message.content'),
            ];
        });
    }

    /** @return Factory<\App\Models\Post>  */
    public function withRealisticTitle(string $language): Factory
    {
        return $this->state(function (array $attributes) use ($language) {
            $prompt = <<<EOD
            Write a nice title for my blog post, keep it short and sweet.
            The language the title should be written in is $language.
            EOD;

            $aiResponse = Http::withToken(config('third-party-api.openai.api_key'))
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

            return [
                'title' => $aiResponse->json('choices.0.message.content'),
            ];
        });
    }

    /** @return Factory<\App\Models\Post>  */
    public function withCodeSnippet(int $count = 1): Factory
    {
        return $this->state(function (array $attributes) use ($count) {
            $markdown = $attributes['markdown'];

            $apiResponses = Http::pool(function (Pool $pool) use ($count) {
                $ocean = [];

                $languages = [
                    'python', 'c', 'rust', 'lua', 'haskell', 'ruby', 'alpine',
                    'css', 'julia', 'ocaml', 'go', 'elixir', 'cpp', 'dockerfile',
                ];

                for ($i = 0; $i < $count; $i++) {
                    $language = Arr::random($languages);

                    $prompt = <<<EOD
                    Please write a medium sized $language snippet,
                    wrapped in markdown fencing, with $language annotated next
                    to the opening fence. Prepend a heading 2 before it,
                    please. Next to some of the code lines, I want you to add
                    some special annotations. I want one line appended with a
                    '[tl! ~~]', one with '[tl! ++]', and one with '[tl! --]'.
                    They should be wrapped in a comment syntax.
                    EOD;

                    $ocean[] = $pool
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

            foreach ($apiResponses as $response) {
                $markdown .= "\n\n".$response->json('choices.0.message.content');
            }

            return compact('markdown');
        });
    }

    /** @return Factory<\App\Models\Post>  */
    public function withVideo(int $count = 1): Factory
    {
        return $this->state(function (array $attributes) use ($count) {
            $youtubeUrl = 'https://www.youtube.com/watch?v=';

            $markdown = $attributes['markdown'];

            $videos = [
                "{$youtubeUrl}OQcSIRZYPgc",
                "{$youtubeUrl}UtfkrGRK8wA",
                "{$youtubeUrl}dQw4w9WgXcQ",
            ];

            for ($i = 0; $i < $count; $i++) {
                $videoSectionHeading = fake()->sentence();

                $video = Arr::random($videos);

                $videoSection = "\n\n## {$videoSectionHeading}\n\n{$video}";

                $markdown .= "\n\n".$videoSection;
            }

            return compact('markdown');
        });
    }

    private function getFrontmatter(): string
    {
        return <<<'EOD'
        ---
        description: test
        tags:
            - test0
            - test1
            - test2
        ---
        EOD;
    }
}
