<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Http::macro('chatWithAI', function (string $prompt) {
            return Http::withToken(config('openai.api_key'))
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post(config('openai.url'), [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $prompt,
                        ],
                    ],
                ]);
        });
    }
}
