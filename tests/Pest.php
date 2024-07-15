<?php

uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

expect()->extend('toBeOne', fn () => $this->toBe(1));

function something()
{
    // ..
}
