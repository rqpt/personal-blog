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
            @if($post->contains_toc)
                <ul>
                    <li
                    x-data="{
                        lightMode: false,
                    }"
                    x-effect="$refs.root.dataset.theme = lightMode ? 'light' : 'dark'"
                    >
                        <svg
                        x-show="!lightMode"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-moon-fill"
                        viewBox="0 0 16 16"
                        >
                             <path
                             d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278"
                             />
                        </svg>

                        <svg
                        x-show="lightMode"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-sun-fill"
                        viewBox="0 0 16 16"
                        >
                             <path
                             d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708"
                             />
                        </svg>

                        <input
                        @click="lightMode = !lightMode"
                        name="terms"
                        type="checkbox"
                        role="switch"
                        />
                    </li>
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
                </ul>
            @endif

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
                }));
            });
        </script>
    @endif
</x-layout.app>
