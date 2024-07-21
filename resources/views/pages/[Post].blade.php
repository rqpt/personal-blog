<?php

use function Laravel\Folio\middleware;

middleware('heal-url');

?>

<x-layout.app>
    @push('styles')
        @vite(['resources/css/app.css', 'resources/css/torchlight.css'])
    @endpush

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


    @if ($post->containsCode())
        <footer>
            <a href="https://torchlight.dev/">
                Syntax highlighting brought to you by Torchlight! 🔦
            </a>
        </footer>
    @endif

</x-layout.app>
