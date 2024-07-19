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
            $videoSection = "\n\n## {$videoSectionHeading}\n\nhttps://www.youtube.com/watch?v=dQw4w9WgXcQ&pp=ygULcmljayBhc3RsZXk%3D";

            $markdown = $attributes['markdown'] . $videoSection;
            return compact('markdown');
        });
    }
}
