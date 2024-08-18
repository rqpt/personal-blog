<?php

namespace App\Enums;

use Stringable;

enum PostType: int implements Stringable
{
    case REGULAR = 0;
    case PINNED = 1;
    case PROMOTIONAL = 2;
    case PLANNED = 3;

    /** @return string[] */
    public static function asFormOptions(): array
    {
        $formOptions = [];

        $cases = self::cases();

        foreach ($cases as $case) {
            $formOptions[] = (string) $case;
        }

        return $formOptions;
    }

    public function __toString(): string
    {
        return match ($this) {
            self::PROMOTIONAL => 'promotional',
            self::PINNED => 'pinned',
            self::PLANNED => 'planned',
            default => 'regular',
        };
    }
}
