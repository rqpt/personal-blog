<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\{
    Attributes\ObservedBy,
    Model,
};
use Illuminate\Support\Str;

#[ObservedBy([PostObserver::class])]
class Post extends Model
{
    protected $casts = [
        'published' => 'boolean',
    ];

    public function getPrettyTitle(): string
    {
        return Str::of($this->title)->replace('-', ' ')->title();
    }
}
