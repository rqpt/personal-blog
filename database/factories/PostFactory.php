<?php

namespace Database\Factories;

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
            'title' => fake()->sentence(),
            'markdown' => fake()->paragraphs(),
            'published_at' => now(),
        ];
    }

    /** @return Factory<\App\Models\Post>  */
    public function draft(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }

    public function withRealisticMarkdown(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'markdown' => Http::getRandomMarkdown(),
        ]);
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
                    '[tl! ~~]', one appended with '[tl! **]', one with
                    '[tl! ++]', and one with '[tl! --]'. They should be
                    wrapped in a comment syntax.
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
    public function withEmbeddedVideo(int $count = 1): Factory
    {
        return $this->state(function (array $attributes) use ($count) {
            $youtubeUrl = 'https://www.youtube.com/watch?v=';

            $markdown = $attributes['markdown'];

            for ($i = 0; $i < $count; $i++) {
                $videoSectionHeading = fake()->sentence();

                $video = fake()->randomElement([
                    "{$youtubeUrl}3co1Wo9sAc8&pp=ygULa2lkIGhlYWRidXQ%3D",
                    "{$youtubeUrl}UtfkrGRK8wA&pp=ygUOaG9sbHl3b29kIGJhYnk%3D",
                    "{$youtubeUrl}dQw4w9WgXcQ&pp=ygULcmljayBhc3RsZXk%3D",
                ]);

                $videoSection = "\n\n## {$videoSectionHeading}\n\n{$video}";

                $markdown .= "\n\n".$videoSection;
            }

            return compact('markdown');
        });
    }
}
