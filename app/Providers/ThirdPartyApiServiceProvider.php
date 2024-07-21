<?php

namespace App\Providers;

use Embed\Embed;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;

class ThirdPartyApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->callAfterResolving('markdown.environment', function ($markdownEnvironment) {
            $embedLibrary = new Embed();

            $embedLibrary->setSettings([
                'oembed:query_parameters' => [
                    'maxwidth' => 800,
                    'maxheight' => 600,
                ],
            ]);

            $markdownEnvironment->mergeConfig(['embed' => [
                'adapter' => new OscaroteroEmbedAdapter($embedLibrary),
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

        Http::macro('chatWithAI', function (string $prompt) {
            return Http::withToken(config('third-party-api.openai.api_key'))
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post(config('third-party-api.openai.url'), [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $prompt,
                        ],
                    ],
                ])->json('choices.0.message.content');
        });
    }
}
