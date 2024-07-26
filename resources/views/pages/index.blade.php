<?php

use function Laravel\Folio\render;
use App\Models\Post;

render(function ($view) {
    $posts = Post::published()->get();

    return $view->with(compact('posts'));
});

?>

<x-layout.app>
    <header>
        <nav>
            <ul>
            </ul>
            <ul>
                <li>
                    <a
                    href="http://ernst-blog.laravel-sail.site:8080/welcome-1"
                    >
                        <x-fas-question-circle />
                    </a>
                </li>
                <x-theme-toggle />
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <pre
            id="drawing"
            >
                                             ___________________________________________________________________
           ______                           |  ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___________  |
     _____/------|                          | | ` | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 0 | - | = | Backspace | |
    /-----      ||            ________      | |___|___|___|___|___|___|___|___|___|___|___|___|___|___________| |
    ||          ||           /       /      |  _______ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ _______  |
    ||          ||          /_______/       | | Tab   | Q | W | F | P | G | J | L | U | Y | ; | [ | ] |       | |
    ||          ||         /                | |_______|___|___|___|___|___|___|___|___|___|___|___|___|       | |
    ||          ||        /                 | |_______ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___  Enter | |
    ||       ___||           ________       | | Caps  | A | R | S | T | G | M | N | E | I | O | ' | \ |       | |
    ||      /    |          /               | |_______|___|___|___|___|___|___|___|___|___|___|___|___|___    | |
 ___||      \___/          /_______         | |_______ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ _______ ___|   | |
/    |                    /                 | | Shift | X | C | D | V | Z | K | H | , | . | / | Shift | ^ |   | |
\___/                    /________          | |_______|___|___|___|___|___|___|___|___|___|___|_______|___|___| |
                                            |  ______ _____ _____ __________ _____ _____ ______  _____ ___ _____|
                                            | | Ctrl | Win | Alt |   Space  | Alt | Win | Ctrl ||  &lt- | V | -&gt  |
                                            | |______|_____|_____|__________|_____|_____|______||_____|___|_____|
                                            |___________________________________________________________________|
            </pre>
        </section>

        @if($posts->count() > 0)
            <section
            id="links"
            x-data="{ lastFocusedLink: localStorage.getItem('lastFocusedLink') }"
            x-init="document.getElementById(lastFocusedLink)?.focus()"
            @keydown.up="$focus.wrap().next()"
            @keydown.down="$focus.wrap().previous()"
            @keydown.k="$focus.wrap().previous()"
            @keydown.j="$focus.wrap().next()"
            @keydown.tab.prevent="$focus.wrap().next()"
            @keydown.shift.tab.prevent="$focus.wrap().previous()"
            >
                @foreach($posts as $post)
                @php $linkRef = "link-{$loop->iteration}"; @endphp
                    <article
                    x-data="{ clicked: false }"
                    :aria-busy="clicked"
                    :aria-label="Please wait..."
                    >
                        <a
                        x-show="!clicked"
                        id="{{ $linkRef }}"
                        x-ref="{{ $linkRef }}"
                        @click.capture="clicked = true"
                        @keydown.capture.enter="clicked = true; localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                        @mouseenter="$focus.focus($el); localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                        @mouseenter.debounce="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                        wire:navigate.hover
                        href="/{{ $post->urlSlug() }}"
                        >
                            {{ $post->title }}
                        </a>
                    </article>
                @endforeach
            </section>
        @else
            <p>
                No posts found
            </p>
        @endif
    </main>
</x-layout.app>
