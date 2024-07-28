<?php

namespace App\Data;

use Carbon\Carbon;
use WendellAdriel\ValidatedDTO\Attributes\Rules;
use WendellAdriel\ValidatedDTO\Casting\CarbonCast;
use WendellAdriel\ValidatedDTO\Concerns\EmptyRules;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class FrontmatterData extends ValidatedDTO
{
    use EmptyRules;

    #[Rules(['required', 'string'])]
    public string $slug;

    #[Rules(['required', 'string'])]
    public string $title;

    #[Rules(['required', 'string'])]
    public string $excerpt;

    #[Rules(['array'])]
    public array $tags;

    #[Rules(['bool'])]
    public bool $draft;

    #[Rules(['required'])]
    public Carbon $createdAt;

    #[Rules(['required'])]
    public Carbon $updatedAt;

    protected function defaults(): array
    {
        return [
            'tags' => [],
            'draft' => false,
            'author' => 'Pieter Ernst Vermeulen',
        ];
    }

    protected function mapData(): array
    {
        return [
            'date' => 'createdAt',
        ];
    }

    protected function casts(): array
    {
        return [
            'createdAt' => new CarbonCast,
            'updatedAt' => new CarbonCast,
        ];
    }
}
