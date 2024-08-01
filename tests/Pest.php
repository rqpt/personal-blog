<?php

uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

/** @phpstan-ignore variable.undefined */
expect()->extend('toBeOne', fn () => $this->toBe(1));

function something(): void
{
    // ..
}
