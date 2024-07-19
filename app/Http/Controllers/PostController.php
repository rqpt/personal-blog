<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Enums\PostStatus,
    Models\Post,
};

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
            'title' => $request->title,
            'status' => PostStatus::tryFrom((int) $request->published),
            'markdown' => $request->body,
        ];

        $post = Post::create($updateValues);

        $status = $post->status == PostStatus::PUBLISHED
            ? 'published'
            : 'drafted';

        return response()->json([
            'message' => "Post $post->id successfully created and $status.",
            'id' => $post->id,
            'title' => $post->title,
            'status' => $status,
        ]);
    }

    public function update(Request $request, string $postSlugOrID)
    {
        try {
            $post = $this->determinePostFromSlug($postSlugOrID);

            $updateValues = [
                'status' => PostStatus::tryFrom((int) $request->published),
            ];

            if ($request->has('title')) {
                $updateValues['title'] = $request->title;
            }

            if ($request->has('body')) {
                $updateValues['markdown'] = $request->body;
            }

            $post->update($updateValues);

            $originalStatus = $post->getOriginal() == PostStatus::PUBLISHED
                ? 'published'
                : 'drafted';

            $status = $post->status == PostStatus::PUBLISHED
                ? 'published'
                : 'drafted';

            return response()->json([
                'message' => "Post $post->id successfully $status.",
                'post_id' => $post->id,
                'original_title' => $post->getOriginal('title'),
                'current_title' => $post->title,
                'original_status' => $originalStatus,
                'current_status' => $status,
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

            $status = $post->status == PostStatus::PUBLISHED
                ? 'published'
                : 'drafted';

            return response()->json([
                'message' => "Post $post->id successfully deleted.",
                'post_id' => $post->id,
                'title' => $post->title,
                'status_prior_to_deletion' => $status,
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    private function determinePostFromSlug(string $postSlug): Post
    {
        $postId = last(explode('-', $postSlug));

        return Post::findOrFail($postId);
    }
}
