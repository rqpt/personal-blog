<?php

namespace App\Http\Controllers;

use Embed\Embed;
use App\Models\Post;
use Illuminate\{
    Http\Request,
    Support\Str,
};
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\{
    Embed\Bridge\OscaroteroEmbedAdapter,
    Embed\EmbedExtension,
    Autolink\AutolinkExtension,
};

class PostController
{
    public function store(Request $request)
    {
        try {
            $markdownFile = $request->file;

            $post = Post::create(['title' => $request->title]);

            $markdownFileDestination = "pages/originals/{$post->title}.md";

            Storage::put($markdownFileDestination, $markdownFile);

            $embedLibrary = new Embed();

            $embedLibrary->setSettings([
                'oembed:query_parameters' => [
                    'maxwidth' => 800,
                    'maxheight' => 600,
                ],
            ]);

            $markdown = file_get_contents(
                storage_path("app/$markdownFileDestination")
            );

            $html = Str::markdown(
                $markdown,
                options: [
                    'embed' => [
                        'adapter' => new OscaroteroEmbedAdapter($embedLibrary),
                    ],
                ],
                extensions: [
                    new AutolinkExtension(),
                    new EmbedExtension(),
                ],
            );

            Storage::put("pages/processed/{$post->title}.html", $html);

            return response()->json([
                'message' => 'Post successfully published.',
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, Post $post)
    {
        //
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();

            Storage::delete("pages/originals/{$post->title}.md");
            Storage::delete("pages/processed/{$post->title}.html");

            return response()->json([
                'message' => 'Post successfully deleted.',
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
