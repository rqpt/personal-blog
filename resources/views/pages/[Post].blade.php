<?php

use function Laravel\Folio\middleware;

middleware('heal-url');

?>

<x-layout.app>
    @php
        $stylesheets = [
            'resources/css/app.css',
            'resources/css/torchlight.css',
        ];

        $requiredStylesheets = $post->contains_code ? $stylesheets : [$stylesheets[0]];
    @endphp

    @vite($requiredStylesheets)

    <header >
        <nav>
            <a
            wire:navigate.hover
            @mouseenter="$focus.focus($el)"
            href="/"
            >
                Home
            </a>
        </nav>
    </header>

    <main>
        <div
        x-data
        x-init="$refs.toc.getElementsByTagName('a')[0].focus()"
        @keydown.up.prevent="$focus.next()"
        @keydown.down.prevent="$focus.previous()"
        @keydown.k="$focus.previous()"
        @keydown.j="$focus.next()"
        >
            {!! $post->html !!}
        </div>
    </main>


    @if ($post->contains_code)
        <footer>
            <a href="https://torchlight.dev/">
                Syntax highlighting brought to you by Torchlight! 🔦
            </a>
        </footer>
    @endif

</x-layout.app>
