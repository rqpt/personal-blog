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
            'markdown' => ['required'],
            'published' => 'bool',
        ]);

        $post = Post::create($request->all());

        $stateChange = $post->published ? 'published' : 'drafted';

        return response()->json([
            'message' => "Post successfully created and $stateChange.",
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($request->all());

        $stateChange = $post->published ? 'published' : 'updated';

        return response()->json([
            'message' => "Post successfully $stateChange.",
        ]);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => 'Post successfully deleted.',
        ]);
    }
}
