<?php

use Illuminate\Support\Facades\Storage;
use App\Models\Post;

use function Laravel\Folio\middleware;

middleware('heal-url');

?>

<x-layout.app>
    @push('styles')
        @vite(['resources/css/app.css', 'resources/css/torchlight.css'])
    @endpush

    <div
    x-data
    x-init="$focus.within($refs.toc).first()"
    @keydown.up.prevent="$focus.within($refs.toc).wrap().previous()"
    @keydown.down.prevent="$focus.within($refs.toc).wrap().next()"
    @keydown.k="$focus.within($refs.toc).previous()"
    @keydown.j="$focus.within($refs.toc).next()"
    >
        {!! $post->html !!}
    </div>
</x-layout.app>
