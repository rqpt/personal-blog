<?php

namespace App\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Frontmatter implements Castable
{
    public function __construct(
        public string $title,
        public string $description,
        public array $tags,
        public string $author,
    ) {}

    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class implements CastsAttributes
        {
            public function get(Model $model, string $key, mixed $value, array $attributes): Frontmatter
            {
                $data = json_decode($attributes[$key]);

                return new Frontmatter(
                    title: $data->title,
                    description: $data->description,
                    tags: $data->tags,
                    author: $data->author,
                );
            }

            public function set(Model $model, string $key, mixed $value, array $attributes): array
            {
                if (! $value instanceof Frontmatter) {
                    throw new InvalidArgumentException('A Frontmatter instance is required.');
                }

                $data = [
                    'title' => $value->title,
                    'description' => $value->description,
                    'tags' => $value->tags,
                    'author' => $value->author,
                ];

                return [$key => json_encode($data)];
            }
        };
    }
}
