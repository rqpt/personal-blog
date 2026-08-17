<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class CollectionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Collection::macro('extractUniqueTags', function () {
            /** @var \Illuminate\Support\Collection $this */
            return $this->flatMap(function ($item) {
                return $item->frontmatter->tags ?? [];
            })->unique()->values();
        });
    }
}
