<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;
use Spatie\Sitemap\SitemapGenerator;

use function Laravel\Prompts\info;

Artisan::command('make:config {name}', function (string $name) {
    $configDirectory = base_path('config');

    File::put("$configDirectory/{$name}.php", "<?php\n\nreturn [\n\n//\n\n];");

    info("Config [/config/{$name}.php] created successfully.");
});

Artisan::command('sitemap:generate', function () {
    SitemapGenerator::create(config('app.url'))
        ->writeToFile(public_path('sitemap.xml'));
});

Schedule::command('model:prune')->daily();
Schedule::command('sitemap:generate')->daily();
