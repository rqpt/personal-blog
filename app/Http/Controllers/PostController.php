<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
                'title' => ['required', 'unique:posts,title'],
                'file' => 'required',
            ]);

            Post::create($request->only('title'));

            $this->createPosts($request);

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
        $draftFile = "{$request->title}.md";

        Storage::disk('drafts')->put($draftFile, $draft);
    }
}
