<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'unique:posts,title'],
            'body' => ['required'],
            'published' => 'bool',
        ]);

        $updateValues = [
            ...$request->only(['title', 'published']),
            'markdown' => $request->body,
        ];

        $post = Post::create($updateValues);

        $stateChange = $post->published ? 'published' : 'drafted';

        return response()->json([
            'message' => "Post $post->id successfully created and $stateChange.",
        ]);
    }

    public function update(Request $request, int $postID)
    {
        try {
            $post = Post::findOrFail($postID);

            $updateValues = [
                ...$request->only(['title', 'published']),
            ];

            if ($request->has('body')) {
                $updateValues['markdown'] = $request->body;
            }

            $post->update($updateValues);

            $stateChange = $post->published ? 'published' : 'updated';

            return response()->json([
                'message' => "Post $post->id successfully $stateChange.",
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function destroy(int $postID)
    {
        try {
            $post = Post::findOrFail($postID);

            $post->delete();

            return response()->json([
                'message' => "Post $post->id successfully deleted.",
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
