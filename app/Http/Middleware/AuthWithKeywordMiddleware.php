<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthWithKeywordMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->get('keyword') === config('auth.keyword')){
            return $next($request);
        }
        abort(403);
    }
}
