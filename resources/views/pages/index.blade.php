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
            </ul>
            <ul>
                <li>
                   <form
                   role="search"
                   id="search"
                   >
                      <input
                      name="search"
                      type="search"
                      placeholder="Search"
                      />
                      <input
                      type="submit"
                      value="Search"
                      />
                    </form>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        @if($posts->count() > 0)
            <div
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
            </div>
        @else
            <p>
                No posts found
            </p>
        @endif
    </main>
</x-layout.app>
