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
    @keydown.up.prevent="$focus.previous()"
    @keydown.down.prevent="$focus.next()"
    @keydown.k="$focus.previous()"
    @keydown.j="$focus.next()"
    >
        {!! $post->html !!}
    </div>
</x-layout.app>
