<?php

namespace App\Containers\Authentication\Middlewares;

use App\Containers\Account\Repositories\AccountRepository;
use App\Ship\Core\Abstracts\Middlewares\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserStatusCheckMiddleware extends Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards): mixed
    {
        $repository = app(AccountRepository::class);

        if ($repository->isAccountBlocked()) {
            return Response::json('Account Has Been Blocked', 403);
        }

        return $next($request);
    }
}
