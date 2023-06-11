<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roles = explode(',', $request->user()->menuroles);
        if (! in_array('admin', $roles)) {
            return abort(401);
        }

        return $next($request);
    }
}
