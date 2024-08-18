<?php

use App\Models\Post;

use function Laravel\Folio\render;

render(function ($view) {
    $latestPosts = Post::latest()->get();
    $pinnedPosts = Post::pinned()->get();
    $promotionalPosts = Post::promotional()->get();

    return $view->with(
        compact(
            'latestPosts',
            'pinnedPosts',
            'promotionalPosts',
        ),
    );
});

?>

<x-layout.app>
    <header>
        <nav>
            <ul>
            </ul>
            <ul>
                <li
                x-data="{ clicked: false }"
                :aria-busy="clicked"
                :aria-label="'Please wait...'"
                >
                    <a
                    x-show="!clicked"
                    @click.capture="clicked = true"
                    @keydown.capture.enter="clicked = true"
                    @mouseenter="$el.focus()"
                    @mouseleave="$el.blur()"
                    data-tooltip="Help"
                    data-placement="left"
                    href="/welcome-1"
                    aria-label="Help shortcut"
                    wire:navigate.hover
                    >
                        <x-fas-question-circle/>
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
            PE Vermeulen - Software Engineer
        </h1>

        <div
        id="categories"
        >
            <x-category-cards
            heading="Pinned"
            :posts="$pinnedPosts"
            />

            <x-category-cards
            heading="Latest"
            :posts="$latestPosts"
            />

            <x-category-cards
            heading="Promotional"
            :posts="$promotionalPosts"
            />

            <x-draft-cards />
        </div>
    </main>
</x-layout.app>
