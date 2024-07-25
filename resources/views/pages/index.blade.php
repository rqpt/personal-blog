<?php

use function Laravel\Folio\render;
use App\Models\Post;

render(function ($view) {
    $posts = Post::published()->get();

    return $view->with(compact('posts'));
});

?>

<x-layout.app
x-data="{ smallScreen: window.matchMedia('(max-width: 640px)').matches }"
>
    <header>
        <nav>
            <ul>
                <x-theme-toggle />
            </ul>
            <template
            x-if="!smallScreen"
            >
                <ul>
                    <li>
                       <form
                       role="search"
                       id="search"
                       >
                          <input
                          name="search"
                          type="search"
                          />
                          <input
                          type="submit"
                          value="Search"
                          />
                        </form>
                    </li>
                </ul>
            </template>
        </nav>
    </header>
    <main>
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

                <template
                x-if="smallScreen"
                >
                   <form
                   role="search"
                   id="search"
                   >
                      <input
                      type="submit"
                      value="Search"
                      />
                      <input
                      name="search"
                      type="search"
                      />
                    </form>
                </template>
            </section>
        @else
            <p>
                No posts found
            </p>
        @endif
    </main>
</x-layout.app>
