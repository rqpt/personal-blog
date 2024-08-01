<?php

namespace App\Enums;

enum PostType: int
{
    case REGULAR = 0;
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

    public static function fromString(string $string): self
    {
        return match ($string) {
            'promotional' => self::PROMOTIONAL,
            'pinned' => self::PINNED,
            default => self::REGULAR,
        };
    }
}
