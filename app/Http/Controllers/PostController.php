<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => ['required', 'unique:posts,title'],
                'body' => ['required'],
                'published' => 'bool',
            ]);

            Post::create($request->validated());

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
            $request->validate([
                'title' => ['required', 'unique:posts,title'],
                'published' => 'bool',
            ]);

            $post->update($request->all());

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
}
