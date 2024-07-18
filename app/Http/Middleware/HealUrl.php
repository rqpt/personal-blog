<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\{
    Http\Request,
};

class HealUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        $postId = last(explode('-', $path));

        $post = Post::findOrFail($postId);

        $trueUrl = $post->getUrlSlug();

        if ($trueUrl !== $path) {
            $trueUrl = url()->query($trueUrl, $request->query());

            return redirect($trueUrl, 301);
        }

        return $next($request);
    }
}
