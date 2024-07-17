<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum TextEditor: string
{
    case BUILTIN = 'builtin';
    case NVIM = 'nvim';
    case VIM = 'vim';
    case VI = 'vi';
    case HELIX = 'helix';
    case MICRO = 'micro';
    case NANO = 'nano';

    public static function getSelectLabels(): array
    {
        $labels = [];

        $textEditors = Arr::pluck(self::cases(), 'value');

        foreach ($textEditors as $editor) {
            $labels[$editor] = match ($editor) {
                self::BUILTIN->value => 'ehm...wut? 😕',
                self::NVIM->value => 'Nvim 😎',
                self::VIM->value => 'Vim 👴',
                self::VI->value => 'Vi 💀',
                self::HELIX->value => 'Helix (deez nutz) 🍆',
                self::MICRO->value => 'Micro 🤏',
                self::NANO->value => 'Nano ⚛️',
            };
        }

        return $labels;
    }
}
