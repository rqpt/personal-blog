<?php

use App\Models\Post;

use function Laravel\Folio\render;

render(function ($view) {
    $posts = Post::metaInfo()->get();

    return $view->with(compact('posts'));
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
    <h1 class="hidden">
      PE Vermeulen - Software Developer
    </h1>

    @if ($posts->count() > 0)
      <section id="posts">
        @foreach ($posts as $post)
          @php $linkRef = "link-{$loop->iteration}"; @endphp

          <article
            x-data="{
                clicked: false,
                focused: false,
            }"
            @mouseenter="focused = true"
            @mouseleave="focused = false"
          >
            <a
              id="{{ $linkRef }}"
              x-ref="{{ $linkRef }}"
              :aria-busy="clicked"
              :aria-label="clicked && 'Please wait...'"
              @click.capture="clicked = true"
              @keydown.capture.enter="clicked = true; localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
              @mouseenter="$el.focus(); localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
              @mouseenter.debounce="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
              wire:navigate
              href="/{{ $post->urlSlug() }}"
            >
              <hgroup x-show="!clicked">
                <h2>
                  {{ $post->title }}
                </h2>

                <p
                  class="mobile-hidden"
                  x-show="focused"
                  x-collapse
                  x-cloak
                >
                  <small>
                    {{ $post->frontmatter->description }}
                  </small>
                </p>
              </hgroup>
            </a>

            <footer>
              <small>
                <em>
                  {{ $post->timestamps() }}
                </em>
              </small>
            </footer>
          </article>
        @endforeach
      </section>
    @endif
  </main>
</x-layout.app>
