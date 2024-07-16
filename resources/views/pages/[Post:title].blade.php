<?php

use function Laravel\Folio\render;
use App\Models\Post;

render(function ($view, Post $post) {
    try {
        $html = file_get_contents(
            storage_path("app/pages/processed/{$post->title}.html")
        );
    } catch (\Throwable) {
        abort(404);
    }

    return  $view->with(compact('html'));
})

?>

<x-layout.app>
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
