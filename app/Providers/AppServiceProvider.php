<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $inDevelopmentEnvironment = $this->app->environment('local');

        // \Debugbar::disable();

        Model::unguard();
        Model::preventLazyLoading($inDevelopmentEnvironment);
        ! $inDevelopmentEnvironment && URL::forceScheme('https');
    }
}
