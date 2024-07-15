<?php

namespace App\Http\Controllers;

use Embed\Embed;
use App\Models\Post;
use Illuminate\{
    Support\Facades\Storage,
    Http\Request,
    Support\Str,
};
use League\CommonMark\Extension\{
    Autolink\AutolinkExtension,
    Embed\Bridge\OscaroteroEmbedAdapter,
    Embed\EmbedExtension,
};
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;

class PostController
{
    public function store(Request $request)
    {
        try {
            $post = Post::create(['title' => $request->title]);

            $this->createPages($request, $post);

            return response()->json([
                'message' => 'Post successfully published.',
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, Post $post)
    {
        try {
            Storage::delete("pages/originals/{$post->title}.md");
            Storage::delete("pages/processed/{$post->title}.html");

            $post->update(['title' => $request->title]);

            $this->createPages($request, $post);

            return response()->json([
                'message' => 'Post successfully updated.',
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
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

    private function createPages(Request $request, Post $post)
    {
        $markdownFile = $request->file;

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
                'heading_permalink' => ['symbol' => ''],
                'table_of_contents' => [
                    'min_heading_level' => 2,
                    'position' => 'placeholder',
                    'placeholder' => '[TOC]',
                ],
                'embed' => [
                    'adapter' => new OscaroteroEmbedAdapter($embedLibrary),
                ],
            ],
            extensions: [
                new HeadingPermalinkExtension(),
                new TableOfContentsExtension(),
                new AutolinkExtension(),
                new EmbedExtension(),
            ],
        );

        Storage::put("pages/processed/{$post->title}.html", $html);
    }
}
