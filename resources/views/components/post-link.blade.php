<li>
    <a
    wire:navigate.hover
    href="/{{ $post->urlSlug() }}"
    >
        {{ $post->prettyTitle() }}
    </a>
</li>
