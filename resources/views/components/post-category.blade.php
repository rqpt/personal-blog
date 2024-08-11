@if ($posts->count() > 0)
    <section>
        <h2>{{ $heading }}</h2>
        @foreach($posts as $post)
            @php $linkRef = "link-{$loop->iteration}"; @endphp

            <a
            x-data="{ clicked: false }"
            id="{{ $linkRef }}"
            x-ref="{{ $linkRef }}"
            @click.capture="clicked = true"
            @keydown.capture.enter="clicked = true; localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
            @mouseenter="$focus.focus($el); localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
            @mouseenter.debounce="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
            wire:navigate.hover
            href="/{{ $post->urlSlug() }}"
            >
                <article
                :aria-busy="clicked"
                :aria-label="'Please wait...'"
                >
                    <hgroup
                    x-show="!clicked"
                    >
                        <h3>
                            {{ $post->title }}
                        </h3>

                        <p>
                            <small>
                                {{ $post->frontmatter->description }}
                            </small>
                        </p>
                    </hgroup>

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
            </a>
        @endforeach
    </section>
@endif
