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
    {!! $html !!}
</x-layout.app>
