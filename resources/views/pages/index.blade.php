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
                    href="/welcome-1"
                    aria-label="Help shortcut"
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
            PE Vermeulen | Blog
        </h1>

        <x-post-category
        heading="Pinned"
        :posts="$pinnedPosts"
        />

        <div
        id="latest-and-promotional-posts"
        >
            <x-post-category
            heading="Latest"
            :posts="$latestPosts"
            />

            <x-post-category
            heading="Promotional"
            :posts="$promotionalPosts"
            />
        </div>
    </main>
</x-layout.app>
