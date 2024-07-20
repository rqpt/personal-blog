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
    @keydown.up.prevent="$focus.previous()"
    @keydown.down.prevent="$focus.next()"
    @keydown.k="$focus.previous()"
    @keydown.j="$focus.next()"
    >
        {!! $post->html !!}
    </div>
</x-layout.app>
