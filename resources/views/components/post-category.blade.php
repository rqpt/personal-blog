@if ($posts->count() > 0)
    <section>
        <h2>{{ $heading }}</h2>
        @foreach($posts as $post)
            @php $linkRef = "link-{$loop->iteration}"; @endphp

            <article
            x-data="{ clicked: false }"
            :aria-busy="clicked"
            :aria-label="'Please wait...'"
            >
                <header>
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
                        <strong>
                            {{ $post->title }}
                        </strong>
                    </a>
                </header>

                <small>
                    {{ $post->frontmatter->description }}
                </small>

                <footer>
                    <small>
                        <em
                        data-tooltip="{{ $post->timestampTooltip() }}"
                        >
                            {{ $post->timestamps() }}
                        </em>
                    </small>
                </footer>
            </article>

        @endforeach
    </section>
@endif
