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
        x-animate
        x-data="{
            showFirstParagraph: false,
            showSecondParagraph: false,
            showHelp: false,
        }"
        >
            <template
            x-if="showFirstParagraph"
            x-init="setTimeout(() => showFirstParagraph = true, 500)"
            >
                <p>
                    Oh no, it looks like we dont have any posts...
                </p>
            </template>

            <template
            x-if="showSecondParagraph"
            x-init="setTimeout(() => showSecondParagraph = true, 1500)"
            >
                <div>
                    <p>
                        What ever shall we do? 🤔
                    </p>

                    <button
                    @click="setTimeout(() => showHelp = true, 500); showSecondParagraph = false; showFirstParagraph = false"
                    >
                        💡
                    </button>
                </div>
            </template>

            <div
            x-cloak
            x-show="showHelp"
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
