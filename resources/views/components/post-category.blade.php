@if ($posts->count() > 0)
    <section>
        <h2>{{ $heading }}</h2>
        @foreach($posts as $post)
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
                    :aria-label="'Please wait...'"
                    @click.capture="clicked = true"
                    @keydown.capture.enter="clicked = true; localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    @mouseenter="$el.focus(); localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    @mouseenter.debounce="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                    wire:navigate
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
                            x-show="focused"
                            x-collapse
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
                            data-placement="right"
                            >
                                {{ $post->timestamps() }}
                            </em>
                        </small>
                    </footer>
                </article>
        @endforeach
    </section>
@endif
