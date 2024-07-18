<?php

use Illuminate\Support\Facades\Storage;
use App\Models\Post;

use function Laravel\Folio\{render, middleware};

middleware('heal');

render(function ($view, Post $post) {
    $html = Storage::disk('published')
        ->get("{$post->title}.html");

    return $view->with(compact('html'));
})

?>

<x-layout.app>
    <style>
        .heading-permalink {
            font-size: .8em;
            vertical-align: super;
            text-decoration: none;
            color: transparent;
        }

        h1:hover .heading-permalink,
        h2:hover .heading-permalink,
        h3:hover .heading-permalink,
        h4:hover .heading-permalink,
        h5:hover .heading-permalink,
        h6:hover .heading-permalink,
        .heading-permalink:hover {
            text-decoration: none;
            color: #777;
        }
    </style>

    <div
    x-data
    x-init="$focus.within($refs.toc).first()"
    @keydown.up.prevent="$focus.within($refs.toc).wrap().previous()"
    @keydown.down.prevent="$focus.within($refs.toc).wrap().next()"
    @keydown.k="$focus.within($refs.toc).wrap().previous()"
    @keydown.j="$focus.within($refs.toc).wrap().next()"
    @keydown.tab.prevent="$focus.within($refs.toc).wrap().next()"
    @keydown.shift.tab.prevent="$focus.within($refs.toc).wrap().previous()"
    >
        {!! $html !!}
    </div>
</x-layout.app>
