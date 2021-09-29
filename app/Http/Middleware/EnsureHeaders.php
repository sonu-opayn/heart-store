<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\HeartStore\ApiResponse;

class EnsureHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->expectsJson()) {
            return ApiResponse::errorGeneral('Accept json header is missing. Please add header.');
        }

        return $next($request);
    }
}
