<?php

namespace App\Ship\Core\Abstracts\Controllers;

use App\Containers\Authentication\Adapters\Cookie as CookieAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

abstract class ApiController extends Controller
{
    /**
     * @param array $tokens
     * @return JsonResponse
     */
    protected function sendLoginResponse(array $tokens): JsonResponse
    {
        return Response::json([
            'token' => [
                'token_type' => $tokens['token_type'],
                'expires_in' => $tokens['expires_in'],
                'access_token' => $tokens['access_token'],
            ]
        ])
            ->withCookie(CookieAdapter::make($tokens['refresh_token']))
            ;
    }
}
