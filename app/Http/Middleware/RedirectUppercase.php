<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectUppercase
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        $pathToLower = strtolower($path);

        if ($pathToLower !== $path) {
            $url = url()->query($pathToLower, $request->query());

            return redirect($url, 301);
        }

        return $next($request);
    }
}
