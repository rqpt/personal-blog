<?php

use function Laravel\Folio\render;
use App\Models\Post;

render(fn ($view) => $view->with('posts', Post::all()));

?>

<x-layout.app>
    <ul>
        @each('components.post-link', $posts, 'post')
    </ul>

    @if(count($posts) == 0)
        <p>No posts found</p>
    @endif
</x-layout.app>
