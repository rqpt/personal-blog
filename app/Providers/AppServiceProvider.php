<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{
    ServiceProvider,
    Facades\URL,
};

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $inDevelopmentEnvironment = $this->app->environment('local');

        Model::unguard();
        Model::preventLazyLoading($inDevelopmentEnvironment);
        !$inDevelopmentEnvironment && URL::forceScheme('https');
    }
}
