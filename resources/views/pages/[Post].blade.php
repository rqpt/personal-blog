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
            <x-theme-toggle />

            @if($post->contains_toc)
                <ul>
                    <li>
                        <button
                        id="toc-button"
                        x-ref="toc-button"
                        x-init="$el.focus()"
                        x-intersect:enter="$el.focus({ preventScroll: true })"
                        x-intersect:leave="$el.blur(); atTopOfPage = false"
                        @click="tocExpanded = !tocExpanded"
                        >
                            <x-carbon-table-of-contents />
                        </button>
                    </li>
                </ul>
            @endif

            <ul>
                <li>
                    <a
                    wire:navigate.hover
                    @mouseenter="$focus.focus($el)"
                    href="/"
                    >
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="#c2c7d0"
                        class="bi bi-house-fill"
                        viewBox="0 0 16 16"
                        >
                          <path
                          d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"
                          />
                          <path
                          d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"
                          />
                        </svg>
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
                    placeholder="Share your thoughts..."
                    aria-label="comment input"
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
                         this.$refs.toc-button.focus();
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
</x-layout.app>
