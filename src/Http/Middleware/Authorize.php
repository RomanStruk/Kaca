<?php

declare(strict_types=1);

namespace Kaca\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kaca\Kaca;

class Authorize
{
    /**
     * Handle the incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        return Kaca::check($request) ? $next($request) : abort(403);
    }
}
