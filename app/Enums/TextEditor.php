<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum TextEditor: string
{
    case BUILTIN = 'builtin';
    case VI = 'vi';
    case NANO = 'nano';

    /** @return array<int|string, string>  */
    public static function selectLabels(): array
    {
        $labels = [];

        $textEditors = Arr::pluck(self::cases(), 'value');

        foreach ($textEditors as $editor) {
            $labels[$editor] = match ($editor) {
                self::VI->value => 'Vi 😎',
                self::NANO->value => 'Nano ⚛️',
                default => 'ehm...wut? 😕',
            };
        }

        return $labels;
    }
}
