<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum TextEditor: string
{
    case BUILTIN = 'builtin';
    case VIM = 'vim';
    case NANO = 'nano';

    /** @return array<int|string, string>  */
    public static function selectOptions(): array
    {
        $labels = [];

        $textEditors = Arr::pluck(self::cases(), 'value');

        foreach ($textEditors as $editor) {
            $labels[$editor] = match ($editor) {
                self::VIM->value => 'Vim 😎',
                self::NANO->value => 'Nano ⚛️',
                default => $editor,
            };
        }

        return $labels;
    }

    public static function selectLabel(): string
    {
        return 'Select your preferred text editor for the post body.';
    }
}
