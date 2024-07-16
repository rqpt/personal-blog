<?php

namespace App\Http\Controllers;

use App\Models\Post;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\{
    Support\Facades\Storage,
    Http\Request,
};

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

        $markdown = file_get_contents(
            storage_path("app/$markdownFileDestination")
        );

        $html = Markdown::convert($markdown)->getContent();

        Storage::put("pages/processed/{$post->title}.html", $html);
    }
}
