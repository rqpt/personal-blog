<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HealUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        $postId = last(explode('-', $path));

        $post = Post::findOrFail($postId);

        $trueUrl = $post->title . '-' . $postId;

        if ($trueUrl !== $path) {
            $trueUrl = url()->query($trueUrl, $request->query());

            return redirect($trueUrl, 301);
        }

        return $next($request);
    }
}
