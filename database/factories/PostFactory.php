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

    public function withTableOfContents(): Factory
    {
        $initialMarkdown = Http::getRandomMarkdown();

        $markdown = preg_replace('/^(# .*)/m', "$1\n\n[TOC]", $initialMarkdown);

        return $this->state(fn (array $attributes) => compact('markdown'));
    }

    public function published(): Factory
    {
        return $this->withState(PostStatus::PUBLISHED);
    }

    public function drafted(): Factory
    {
        return $this->withState(PostStatus::DRAFT);
    }

    private function withState(PostStatus $status)
    {
        return $this->state(fn (array $attributes) => compact('status'));
    }
}
