<?php

use function Laravel\Folio\render;
use App\Models\Post;

render(function ($view) {
    $posts = Post::published()->get();

    return $view->with(compact('posts'));
});

?>

<x-layout.app>
    @push('styles')
        @vite('resources/css/torchlight.css')
    @endpush

    <main>
        <ul
        x-data="{ lastFocusedLink: localStorage.getItem('lastFocusedLink') }"
        x-init="document.getElementById(lastFocusedLink)?.focus()"
        @keydown.up="$focus.wrap().next()"
        @keydown.down="$focus.wrap().previous()"
        @keydown.k="$focus.wrap().previous()"
        @keydown.j="$focus.wrap().next()"
        @keydown.tab.prevent="$focus.wrap().next()"
        @keydown.shift.tab.prevent="$focus.wrap().previous()"
        >
            @foreach ($posts as $post)
            @php $linkRef = "link-{$loop->iteration}"; @endphp
                <li>
                    <a
                    id="{{ $linkRef }}"
                    x-ref="{{ $linkRef }}"
                    @keydown.capture.enter="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    @mouseenter="$focus.focus($el); localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    @mouseenter.debounce="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    wire:navigate.hover
                    href="/{{ $post->urlSlug() }}"
                    >
                        {{ $post->title }}
                    </a>
                </li>
            @endforeach
        </ul>

        @if ($posts->count() == 0)
            <p>
                No posts found
            </p>
        @endif
    </main>
</x-layout.app>
