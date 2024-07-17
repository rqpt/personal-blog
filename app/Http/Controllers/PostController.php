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
            $request->validate([
                'title' => 'required',
                'file' => 'required',
            ]);

            $this->createPosts($request);

            Post::create($request->only('title'));

            return response()->json([
                'message' => 'Draft post successfully created.',
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, Post $post)
    {
        try {
            $post->update($request->except('file'));

            $this->createPosts($request);

            $stateChange = $request->published ? 'published' : 'updated';

            return response()->json([
                'message' => "Post successfully $stateChange.",
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();

            return response()->json([
                'message' => 'Post successfully deleted.',
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    private function createPosts(Request $request)
    {
        $draft = $request->file;

        $postsPath = 'posts';

        $draftPath = "$postsPath/drafts/{$request->title}.md";
        $publishedPath = "$postsPath/published/{$request->title}.html";

        Storage::put($draftPath, $draft);

        if ($request->published) {
            $markdown = Storage::get($draftPath);

            $html = Markdown::convert($markdown)->getContent();

            Storage::put($publishedPath, $html);
        }
    }
}
