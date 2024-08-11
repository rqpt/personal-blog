<?php

namespace App\Providers;

use Embed\Embed;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;

class MarkdownServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->callAfterResolving('markdown.environment', function ($markdownEnvironment) {
            $embedLibrary = new Embed;

            $embedLibrary->setSettings([
                'oembed:query_parameters' => [
                    'maxwidth' => 800,
                    'maxheight' => 600,
                ],
            ]);

            $markdownEnvironment->mergeConfig(['embed' => [
                'adapter' => new OscaroteroEmbedAdapter($embedLibrary),
                'allowed_domains' => ['youtube.com'],
                'fallback' => 'link',
            ]]);
        });
    }

    public function boot(): void
    {
        Http::macro('getRandomMarkdown', function () {
            return Http::get(config('third-party-api.random_markdown.url'), [
                'no-code' => 'on',
            ])->body();
        });
    }
}
