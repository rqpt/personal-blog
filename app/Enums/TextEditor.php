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

    public function editorSensibleDefaultSettings(): string
    {
        if ($this === self::VIM) {
            return <<<EOD
                +set numberwidth=3 shiftwidth=2 tabstop=2 softtabstop=2 expandtab
                smartindent number relativenumber ignorecase smartcase breakindent
                splitbelow splitright scrolloff=20 sidescrolloff=15 confirm
                noswapfile wildmode=longest:full,full isfname+=@-@ lazyredraw
                completeopt=menuone,longest,preview incsearch updatetime=50
                showmode conceallevel=2 foldlevel=99 cmdheight=1
                EOD;
        } else {
            return '';
        }
    }
}
