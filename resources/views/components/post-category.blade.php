@if ($posts->count() > 0)
    <section>
        <h2>{{ $heading }}</h2>
        @foreach($posts as $post)
            @php $linkRef = "link-{$loop->iteration}"; @endphp

                <article>
                    <a
                    id="{{ $linkRef }}"
                    x-data="{ clicked: false }"
                    x-ref="{{ $linkRef }}"
                    :aria-busy="clicked"
                    :aria-label="'Please wait...'"
                    @click.capture="clicked = true"
                    @keydown.capture.enter="clicked = true; localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    @mouseenter="$focus.focus($el); localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    @mouseenter.debounce="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    wire:navigate.hover
                    href="/{{ $post->urlSlug() }}"
                    >
                        <hgroup
                        x-show="!clicked"
                        >
                            <h3>
                                {{ $post->title }}
                            </h3>

                            <p
                            class="mobile-hidden"
                            >
                                <small>
                                    {{ $post->frontmatter->description }}
                                </small>
                            </p>
                        </hgroup>
                    </a>

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
