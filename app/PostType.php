<?php

namespace App;

enum PostType: int
{
    case REGULAR = 0;
    case PINNED = 1;
    case PROMOTIONAL = 2;

    public static function asFormOptions(): array
    {
        $formOptions = [];

        $cases = self::cases();

        foreach ($cases as $case) {
            $formOptions[] = $case->asString();
        }

        return $formOptions;
    }

    public function asString(): string
    {
        return match ($this) {
            self::PROMOTIONAL => 'promotional',
            self::PINNED => 'pinned',
            default => 'regular',
        };
    }
}
