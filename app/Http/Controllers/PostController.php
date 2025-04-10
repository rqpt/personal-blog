<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController
{
    public function index(): AnonymousResourceCollection
    {
        return PostResource::collection(Post::all());
    }

    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    public function store(Request $request): PostResource
    {
        $request->validate([
            'title' => ['required', 'unique:posts,title'],
            'body' => ['required'],
        ]);

        $post = Post::create([
            'title' => $request->title,
            'markdown' => $request->body,
            'published_at' => now(),
        ]);

        return new PostResource($post);
    }

    public function update(Request $request, Post $post): PostResource
    {
        $updateValues = [];

        if ($request->has('title')) {
            $updateValues['title'] = $request->title;
        }

        if ($request->has('body')) {
            $updateValues['markdown'] = $request->body;
        }

        $post->update($updateValues);

        return new PostResource($post);
    }

    public function destroy(Post $post): PostResource
    {
        $post->delete();

        return new PostResource($post);
    }
}
