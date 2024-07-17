<li>
    <a
    wire:navigate.hover
    href="/{{ $post->getUrlSlug() }}"
    >
        {{ $post->getPrettyTitle() }}
    </a>
</li>
