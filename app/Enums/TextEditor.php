<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum TextEditor: string
{
    case BUILTIN = 'builtin';
    case VI = 'vi';
    case NANO = 'nano';

    public static function getSelectLabels(): array
    {
        $labels = [];

        $textEditors = Arr::pluck(self::cases(), 'value');

        foreach ($textEditors as $editor) {
            $labels[$editor] = match ($editor) {
                self::BUILTIN->value => 'ehm...wut? 😕',
                self::VI->value => 'Vi 😎',
                self::NANO->value => 'Nano ⚛️',
            };
        }

        return $labels;
    }
}
