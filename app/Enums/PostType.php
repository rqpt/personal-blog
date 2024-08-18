<?php

namespace App\Enums;

enum PostType: int
{
    case LATEST = 0;
    case PINNED = 1;
    case PROMOTIONAL = 2;

    /** @return string[] */
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
