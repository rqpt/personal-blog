<?php

namespace App\Enums;

enum PostStatus: int
{
    case DRAFT = 0;
    case PUBLISHED = 1;

    public function forHumans(): string
    {
        return match ($this) {
            self::PUBLISHED => 'published',
            self::DRAFT => 'drafted',
        };
    }
}
