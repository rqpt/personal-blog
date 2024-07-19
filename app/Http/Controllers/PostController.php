<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Http\Resources\PostResource,
    Enums\PostStatus,
    Models\Post,
};

class PostController
{
    public function index()
    {
        return PostResource::collection(Post::all());
    }

    public function show(string $postSlugOrID)
    {
        try {
            $post = $this->determinePostFromSlug($postSlugOrID);
            return new PostResource($post);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

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

        return new PostResource($post);
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

            return new PostResource($post);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function destroy(string $postSlugOrID)
    {
        try {
            $post = $this->determinePostFromSlug($postSlugOrID);

            $post->delete();

            return new PostResource($post);
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
