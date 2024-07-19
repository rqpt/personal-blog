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
    public function index(Request $request)
    {
        if ($request->has('published')) {
            $statusType = PostStatus::from($request->published);

            $posts = Post::where('status', $statusType)->get();
        } else {
            $posts = Post::all();
        }

        return PostResource::collection($posts);
    }

    public function show(string $postSlugOrID)
    {
        $post = $this->determinePostFromSlug($postSlugOrID);

        return new PostResource($post);
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
    }

    public function destroy(string $postSlugOrID)
    {
        $post = $this->determinePostFromSlug($postSlugOrID);

        $post->delete();

        return new PostResource($post);
    }

    private function determinePostFromSlug(string $postSlug): Post
    {
        $postId = last(explode('-', $postSlug));

        return Post::findOrFail($postId);
    }
}
