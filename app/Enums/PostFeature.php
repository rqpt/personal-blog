<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum PostFeature: int
{
    case VIDEO = 0b00000001;
    case CODE = 0b00000010;

    public static function collect(): Collection
    {
        return collect(self::cases());
    }
}
