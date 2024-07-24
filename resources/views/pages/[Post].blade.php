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

    <main
    x-data="{
        tocExpanded: false,
        atTopOfPage: true,
    }"
    >
        @if($post->contains_toc)
            <button
            x-ref="tocButton"
            x-init="$el.focus()"
            x-intersect:enter="$el.focus({ preventScroll: true })"
            x-intersect:leave="$el.blur(); atTopOfPage = false"
            @click="tocExpanded = !tocExpanded"
            >
                Table of Contents
            </button>
        @endif

        {!! $post->html !!}

    </main>

    @if($post->contains_code)
        <footer x-data>
            <a
            x-intersect:enter="$el.focus({ preventScroll: true })"
            x-intersect:leave="$el.blur()"
            href="https://torchlight.dev/"
            >
                Syntax highlighting brought to you by Torchlight! 🔦
            </a>
        </footer>
    @endif

    @if($post->contains_toc)
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.bind('toc', () => ({
                    '@keydown.escape'() {
                         this.tocExpanded = false;
                         this.$refs.tocButton.focus();
                    },

                    '@keydown.j'() {
                         this.$focus.wrap().next();
                    },

                    '@keydown.k'() {
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
    @endif
</x-layout.app>
