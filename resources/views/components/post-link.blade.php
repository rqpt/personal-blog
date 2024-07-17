<li>
    <a
    wire:navigate.hover
    href="/{{ $post->title }}"
    >
        {{ $post->getPrettyTitle() }}
    </a>
</li>
