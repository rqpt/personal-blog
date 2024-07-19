<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use Illuminate\{
    Database\Eloquent\Factories\Factory,
    Support\Facades\Http,
};

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

    public function published(): Factory
    {
        return $this->withState(PostStatus::PUBLISHED);
    }

    public function drafted(): Factory
    {
        return $this->withState(PostStatus::DRAFT);
    }

    private function withState(PostStatus $status): Factory
    {
        return $this->state(function (array $attributes) use ($status) {
            return compact('status');
        });
    }

    public function withTableOfContents(): Factory
    {
        return $this->state(function (array $attributes) {
            $markdown = preg_replace('/^(# .*)/m', "$1\n\n[TOC]", $attributes['markdown']);
            return compact('markdown');
        });
    }

    public function withAnEmbeddedVideo(): Factory
    {
        return $this->state(function (array $attributes) {
            $videoSectionHeading = fake()->sentence();

            $video = fake()->randomElement([
                'https://www.youtube.com/watch?v=3co1Wo9sAc8&pp=ygULa2lkIGhlYWRidXQ%3D',
                'https://www.youtube.com/watch?v=UtfkrGRK8wA&pp=ygUOaG9sbHl3b29kIGJhYnk%3D',
                'https://www.youtube.com/watch?v=dQw4w9WgXcQ&pp=ygULcmljayBhc3RsZXk%3D',
            ]);

            $videoSection = "\n\n## {$videoSectionHeading}\n\n{$video}";

            $markdown = $attributes['markdown'] . $videoSection;
            return compact('markdown');
        });
    }
}
