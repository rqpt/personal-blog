<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->has('published')) {
            $posts = Post::whereNotNull('published_at')->get();
        } else {
            $posts = Post::all();
        }

        return PostResource::collection($posts);
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
            'published' => 'bool',
        ]);

        $updateValues = [
            'title' => $request->title,
            'markdown' => $request->body,
        ];

        if ($request->published) {
            $updateValues['published_at'] = now();
        }

        $post = Post::create($updateValues);

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

        if ($request->has('published') && $request->published) {
            $updateValues['published_at'] = now();
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
