<?php

use function Laravel\Folio\middleware;

middleware('heal-url');

?>

<x-layout.app
x-data="{
    tocExpanded: false,
    atTopOfPage: true,
}"
>
    <header>
        <nav>
            <ul>
                <x-theme-toggle />

                @if($post->contains_toc)
                    <li>
                        <button
                        x-ref="tocButton"
                        x-init="$el.focus()"
                        x-intersect:enter="$el.focus({ preventScroll: true })"
                        x-intersect:leave="$el.blur(); atTopOfPage = false"
                        @click="tocExpanded = !tocExpanded"
                        >
                            Table of Contents
                        </button>
                    </li>
                @endif
            </ul>

            <ul>
                <li>
                    <a
                    wire:navigate.hover
                    @mouseenter="$focus.focus($el)"
                    href="/"
                    >
                        Home
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <section
        id="post-body"
        >
            {!! $post->html !!}
        </section>
    </main>

    <hr>

    <footer x-data>
        <section
        id="comments"
        >
            <form
            action=""
            >
                <fieldset>
                    <label
                    for="author"
                    >
                        Name
                        <input
                        type="text"
                        name="author"
                        id="author"
                        >
                    </label>
                    <textarea
                    type="text"
                    placeholder="Speak your mind..."
                    aria-label="Comment input"
                    name="comment"
                    id="comment"
                    ></textarea>
                </fieldset>
            </form>

            @foreach($post->comments as $comment)
                <div>
                    <p>
                        {{ $comment->author }}
                    </p>
                    <p>
                        {{ $comment->body }}
                    </p>
                </div>
            @endforeach
        </section>

        @if($post->contains_code)
            <a
            x-intersect:enter="$el.focus({ preventScroll: true })"
            x-intersect:leave="$el.blur()"
            href="https://torchlight.dev/"
            >
                Syntax highlighting brought to you by Torchlight! 🔦
            </a>
        @endif
    </footer>

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


                    '@scroll.window.throttle.100s'() {
                        this.tocExpanded = false;
                    },
                }));
            });
        </script>
    @endif
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const iframes = document.querySelectorAll('iframe');

                iframes.forEach(iframe => {
                    const wrapperDiv = document.createElement('div');

                    wrapperDiv.style.display = 'flex';
                    wrapperDiv.style.marginTop = '2rem';
                    wrapperDiv.style.justifyContent = 'center';

                    iframe.parentNode.insertBefore(wrapperDiv, iframe);
                    wrapperDiv.appendChild(iframe);
                });
            });
        </script>
</x-layout.app>
