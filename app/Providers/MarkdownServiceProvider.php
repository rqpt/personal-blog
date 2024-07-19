<?php

namespace App\Providers;

use Illuminate\Support\{
    ServiceProvider,
    Facades\Http,
};

class MarkdownServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Http::macro('getRandomMarkdown', function () {
            return Http::get(config('third-party-api.random_markdown'));
        });
    }
}
