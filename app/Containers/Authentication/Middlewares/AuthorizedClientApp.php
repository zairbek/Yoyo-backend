<?php

namespace App\Containers\Authentication\Middlewares;

use App\Ship\Core\Abstracts\Middlewares\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuthorizedClientApp extends Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards): mixed
    {
        if (is_null($request->header('Client-Id')) || is_null($request->header('Client-Secret'))) {
            return Response::json('Unauthorized: Check please Client Id and Client Secret', 400);
        }

        return $next($request);
    }
}
