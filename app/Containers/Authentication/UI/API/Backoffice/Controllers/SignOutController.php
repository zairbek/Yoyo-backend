<?php

namespace App\Containers\Authentication\UI\API\Backoffice\Controllers;

use App\Containers\Authentication\Adapters\Cookie as CookieAdapter;
use App\Containers\Authentication\Adapters\Passport as PassportAdapter;
use App\Ship\Core\Abstracts\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class SignOutController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * @return JsonResponse
     */
    public function signOut(): JsonResponse
    {
        if (Auth::guard('api')->check()) {
            $token = Auth::user()->token();

            PassportAdapter::revokeAccessAndRefreshTokens($token->id);
            $token->revoke();
        }

        return Response::json('Signed out successfully')
            ->withCookie(CookieAdapter::forget())
        ;
    }
}
