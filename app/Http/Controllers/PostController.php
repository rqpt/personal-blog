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
            'id' => $post->id,
            'title' => $post->title,
            'published' => $post->published,
        ]);
    }

    public function update(Request $request, string $postSlugOrID)
    {
        try {
            $post = $this->determinePostFromSlug($postSlugOrID);

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
                'post_id' => $post->id,
                'original_title' => $post->getOriginal('title'),
                'current_title' => $post->title,
                'original_status' => $post->getOriginal('published'),
                'current_status' => $post->published,
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function destroy(string $postSlugOrID)
    {
        try {
            $post = $this->determinePostFromSlug($postSlugOrID);

            $post->delete();

            return response()->json([
                'message' => "Post $post->id successfully deleted.",
                'post_id' => $post->id,
                'title' => $post->title,
                'status_prior_to_deletion' => $post->published,
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    private function determinePostFromSlug(string $postSlug): Post
    {
        $postID = last(explode('-', $postSlug));

        return Post::findOrFail($postID);
    }
}
