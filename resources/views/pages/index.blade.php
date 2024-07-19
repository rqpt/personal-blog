<?php

use function Laravel\Folio\render;
use App\Models\Post;

render(function ($view) {
    $posts = Post::published()->get();

    return $view->with(compact('posts'));
});

?>

<x-layout.app>
    <ul
    x-data="{ lastFocusedLink: localStorage.getItem('lastFocusedLink') }"
    x-init="document.getElementById(lastFocusedLink)?.focus()"
    @keydown.up="$focus.wrap().previous()"
    @keydown.down="$focus.wrap().next()"
    @keydown.k="$focus.wrap().previous()"
    @keydown.j="$focus.wrap().next()"
    @keydown.tab.prevent="$focus.wrap().next()"
    @keydown.shift.tab.prevent="$focus.wrap().previous()"
    >
        @foreach ($posts as $post)
        @php $linkRef = "link-{$loop->iteration}"; @endphp
            <li>
                <a
                id="{{ $linkRef }}"
                x-ref="{{ $linkRef }}"
                @click="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                @keydown.enter="localStorage.setItem('lastFocusedLink', '{{ $linkRef }}')"
                @mouseenter="$focus.focus($el)"
                wire:navigate.hover
                href="/{{ $post->getUrlSlug() }}"
                >
                    {{ $post->title }}
                </a>
            </li>
        @endforeach
    </ul>

    @if(count($posts) == 0)
        <div
        x-data="{
            intro: true,
            firstParagraph: false,
            secondParagraph: false,
            solutionButton: false,
            help: false,
        }"
        x-init="
        setTimeout(() => firstParagraph = true, 1000);
        setTimeout(() => secondParagraph = true, 3000);
        setTimeout(() => solutionButton = true, 5000);
        "
        >
            <div
            x-show="intro"
            x-transition
            x-transition:leave.duration.0ms
            >
                <p
                x-cloak
                x-show="firstParagraph"
                x-transition
                >
                    Oh no, it looks like we dont have any posts...
                </p>

                <p
                x-cloak
                x-show="secondParagraph"
                x-transition
                >
                    What ever shall we do? 🤔
                </p>

                <button
                x-cloak
                x-show="solutionButton"
                x-transition
                @click="intro = false; help = true"
                >
                    💡
                </button>
            </div>

            <div
            x-cloak
            x-show="help"
            x-transition
            >
                <p>
                    Run the following command, and hit refresh.
                </p>

                <pre><code>http :80/api/post title="{filename}" body="# Hello World" published:=true</code></pre>

                <p>
                    No worries if you made a mistake - simply run the next
                    command to delete the post and try again.
                </p>

                <p>
                    You should be provided with the postID by the output of the
                    previous command.
                </p>

                <pre><code>http DELETE :80/api/{postID}</code></pre>
            </div>
        </div>
    @endif
</x-layout.app>
