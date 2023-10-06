<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustBeModerator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array(auth()->user()?->role, ['mod', 'admin'])) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
