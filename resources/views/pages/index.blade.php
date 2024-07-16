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
                    Run the following command with '{filename}' substituted
                    with a markdown filename, and hit refresh.
                </p>

                <pre><code>http -f POST :80/api/post title="{filename}" file=@{filename}.md</code></pre>

                <p>
                    No worries if you made a mistake - simply run the next
                    command to delete the post and try again.
                </p>

                <pre><code>http DELETE :80/api/{the name of the post you wish to delete}</code></pre>
            </div>
        </div>
    @endif
</x-layout.app>
