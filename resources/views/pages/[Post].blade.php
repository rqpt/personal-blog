<?php

use function Laravel\Folio\middleware;

middleware('heal-url');

?>

<x-layout.app>
    @push('styles')
        @vite(['resources/css/app.css', 'resources/css/torchlight.css'])
    @endpush

    <div
    x-data
    x-init="$refs.toc.getElementsByTagName('a')[0].focus()"
    @keydown.up.prevent="$focus.next()"
    @keydown.down.prevent="$focus.previous()"
    @keydown.k="$focus.previous()"
    @keydown.j="$focus.next()"
    >
        <h1>
            {{ $post->title}}
        </h1>

        {!! $post->html !!}
    </div>
</x-layout.app>
