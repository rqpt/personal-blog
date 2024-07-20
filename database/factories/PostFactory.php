<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

/** @extends Factory<\App\Models\Post> */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'markdown' => Http::getRandomMarkdown(),
            'status' => fake()->randomElement(PostStatus::cases()),
        ];
    }

    /** @return Factory<\App\Models\Post>  */
    public function published(): Factory
    {
        return $this->withState(PostStatus::PUBLISHED);
    }

    /** @return Factory<\App\Models\Post>  */
    public function drafted(): Factory
    {
        return $this->withState(PostStatus::DRAFT);
    }

    /** @return Factory<\App\Models\Post>  */
    private function withState(PostStatus $status): Factory
    {
        return $this->state(function (array $attributes) use ($status) {
            return compact('status');
        });
    }

    /** @return Factory<\App\Models\Post>  */
    public function withARealCodeSnippet(): Factory
    {
        return $this->state(function (array $attributes) {
            $language = fake()->randomElement(['python', 'php', 'c', 'rust']);

            $prompt = <<<EOD
            Please write a medium sized $language snippet,
            wrapped in markdown fencing,
            with $language annotated next to the opening fence.
            Prepend a heading 2 before it, please.";
            EOD;

            $snippet = Http::chatWithAI($prompt)
                ->json('choices.message.content');

            $markdown = $attributes['markdown'].$snippet;

            return compact('markdown');
        });
    }

    /** @return Factory<\App\Models\Post>  */
    public function withAnEmbeddedVideo(): Factory
    {
        return $this->state(function (array $attributes) {
            $videoSectionHeading = fake()->sentence();
            $youtubeUrl = 'https://www.youtube.com/watch?v=';

            $video = fake()->randomElement([
                "{$youtubeUrl}3co1Wo9sAc8&pp=ygULa2lkIGhlYWRidXQ%3D",
                "{$youtubeUrl}UtfkrGRK8wA&pp=ygUOaG9sbHl3b29kIGJhYnk%3D",
                "{$youtubeUrl}dQw4w9WgXcQ&pp=ygULcmljayBhc3RsZXk%3D",
            ]);

            $videoSection = "\n\n## {$videoSectionHeading}\n\n{$video}";

            $markdown = $attributes['markdown'].$videoSection;

            return compact('markdown');
        });
    }
}
