<?php

use function Laravel\Folio\middleware;

middleware('heal-url');

?>

<x-layout.app>
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
        <div x-data="{ tocExpanded: false }">
            <button
            x-ref="tocButton"
            x-init="$el.focus()"
            x-intersect:enter="$el.focus()"
            x-intersect:leave="$el.blur()"
            @click="tocExpanded = true"
            @keydown.j="$focus.next()"
            @keydown.k="$focus.previous()"
            >
                Table of Contents
            </button>

            {!! $post->html !!}
        </div>
    </main>


    @if($post->contains_code)
        <footer>
            <a href="https://torchlight.dev/">
                Syntax highlighting brought to you by Torchlight! 🔦
            </a>
        </footer>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.bind('toc', () => ({
                '@keydown.escape'() {
                     this.tocExpanded = false;
                     this.$refs.tocButton.focus();
                },

                '@keydown.j.stop'() {
                     this.$focus.wrap().next();
                },

                '@keydown.k.stop'() {
                     this.$focus.wrap().previous();
                },

                '@keydown.tab'() {
                     this.$focus.wrap().next();
                },

                '@keydown.shift.tab'() {
                     this.$focus.wrap().previous();
                },
            }));
        });
    </script>
</x-layout.app>
