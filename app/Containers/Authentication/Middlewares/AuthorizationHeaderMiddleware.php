<?php

namespace App\Containers\Authentication\Middlewares;

use App\Ship\Core\Abstracts\Middlewares\Middleware;
use Closure;
use Illuminate\Http\Request;

class AuthorizationHeaderMiddleware extends Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards): mixed
    {
        $request->headers->set('Authorization', $request->headers->get('X-Access-Token'));

        return $next($request);
    }
}
