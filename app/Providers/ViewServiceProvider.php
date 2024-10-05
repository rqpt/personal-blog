<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        seo()
            ->withUrl()
            ->site('PE Vermeulen - Software Developer')
            ->tag('author', 'PE Vermeulen')
            ->title(
                default: 'PE Vermeulen - Software Developer',
                modify: fn (string $title) => $title.' | PE Vermeulen'
            )
            ->description(default: "I am a software engineer and music lover. This is where I'll post whatever I'm interested in.")
            ->image(default: fn () => asset('logo.webp'));
    }
}
