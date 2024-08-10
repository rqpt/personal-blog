<?php

use Illuminate\View\View;
use App\Models\Post;

use function Laravel\Folio\{middleware, render};

middleware('heal-url');

render(function (View $view, Post $post) {
  seo()
      ->tag('author', $post->frontmatter->author)
      ->title($post->frontmatter->title)
      ->description($post->frontmatter->description);

    return $view;
});

?>

<x-layout.app
x-init="illuminateSnippets(lightMode)"
x-effect="illuminateSnippets(lightMode)"
>
    <header
    :id="smallScreen && 'responsive-nav'"
    >
        <nav>
            <ul>
                <li>
                    <button
                    class="pico-button-override"
                    aria-label="Toggle table of contents"
                    x-ref="toc-button"
                    x-init="$el.focus()"
                    x-intersect:enter="$el.focus({ preventScroll: true })"
                    x-intersect:leave="$el.blur(); atTopOfPage = false"
                    @click="tocExpanded = !tocExpanded"
                    >
                        <x-fas-list-ul
                        ::fill="lightMode && '#2d3138'"
                        />
                    </button>
                </li>
            </ul>

            <ul>
                <li>
                    <a
                    wire:navigate.hover
                    @mouseenter="$el.focus()"
                    @mouseleave="$el.blur()"
                    href="/"
                    data-tooltip="Home"
                    data-placement="left"
                    >
                        <x-fas-house />
                    </a>
                </li>
                <x-theme-toggle />
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

    @if ($post->contains_code)
        <hr>

        <footer x-data>
                <a
                x-intersect:enter="$el.focus({ preventScroll: true })"
                x-intersect:leave="$el.blur()"
                href="https://torchlight.dev/"
                >
                    Syntax highlighting provided by Torchlight! 🔦
                </a>
        </footer>
    @endif

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
    <script>
        function illuminateSnippets(lightMode) {
            const snippets = document.querySelectorAll('pre code');

            snippets.forEach(function(snippet) {
                if (lightMode) {
                    if (snippet.getAttribute('data-theme') === 'light') {
                        snippet.style.display = 'block';
                    } else {
                        snippet.style.display = 'none';
                    }
                } else {
                    if (snippet.getAttribute('data-theme') === 'dark') {
                        snippet.style.display = 'block';
                    } else {
                        snippet.style.display = 'none';
                    }
                }
            });
        }
    </script>
</x-layout.app>
