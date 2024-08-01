<?php

use App\Models\Post;

use function Laravel\Folio\render;

render(function ($view) {
    $latestPosts = Post::all();
    $pinnedPosts = Post::pinned()->get();
    $promotionalPosts = Post::promotional()->get();

    return $view->with(
        compact('pinnedPosts', 'latestPosts', 'promotionalPosts'),
    );
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
                    @mouseenter="$el.focus()"
                    @mouseleave="$el.blur()"
                    data-tooltip="Help"
                    data-placement="left"
                    >
                        <x-fas-question-circle />
                    </a>
                </li>
                <x-theme-toggle />
            </ul>
        </nav>
    </header>
    <main
    x-data="{ lastFocusedLink: localStorage.getItem('lastFocusedLink') }"
    x-init="document.getElementById(lastFocusedLink)?.focus()"
    @keydown.up="$focus.wrap().next()"
    @keydown.down="$focus.wrap().previous()"
    @keydown.k="$focus.wrap().previous()"
    @keydown.j="$focus.wrap().next()"
    @keydown.tab.prevent="$focus.wrap().next()"
    @keydown.shift.tab.prevent="$focus.wrap().previous()"
    >
        <h1
        class="hidden"
        >
            My Blog
        </h1>

        @if ($pinnedPosts->count() > 0)
            <section>
                <h2>Pinned</h2>
                @foreach($pinnedPosts as $post)
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
        @endif
        <div
        id="latest-and-promotional-posts"
        >
            @if ($latestPosts->count() > 0)
                <section>
                    <h2>Recent</h2>
                    @foreach($latestPosts as $post)
                        @php $linkRef = "link-{$loop->iteration}"; @endphp

                        <article
                        x-data="{ clicked: false }"
                        :aria-busy="clicked"
                        :aria-label="'Please wait...'"
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
            @endif
            @if ($promotionalPosts->count() > 0)
                <section>
                    <h2>Promotional</h2>
                    @foreach($promotionalPosts as $post)
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
            @endif
        </div>
    </main>
</x-layout.app>
