<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
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
