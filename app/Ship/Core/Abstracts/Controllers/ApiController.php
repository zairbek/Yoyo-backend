<?php

namespace App\Ship\Core\Abstracts\Controllers;

use App\Containers\Authentication\Adapters\Cookie as CookieAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

/**
 * @OA\Info(
 *     title="eBazar API Documentation",
 *     version="1.0",
 * )
 * @OA\Server(
 *     description="eBazar Local Server",
 *     url="http://ebazar.loc",
 * )
 *  @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     in="header",
 *     scheme="Bearer",
 *     bearerFormat="JWT",
 *     name="bearerAuth",
 * )
 */
abstract class ApiController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="AuthResponse",
     *     title="Auth response schema",
     *     @OA\Property(
     *          property="token",
     *          @OA\Property(
     *              property="access_token",
     *              type="string",
     *              example="...asdfhsdfuhdf78d7fadhfad..."
     *          ),
     *          @OA\Property(
     *              property="expires_in",
     *              type="int",
     *              example=86400
     *          ),
     *          @OA\Property(
     *              property="token_type",
     *              type="string",
     *              example="Bearer"
     *          ),
     *     ),
     * ),
     *
     *
     * @OA\Header(
     *     header="RefreshToken",
     *     description="Refresh-Token возвращается в куки с http-only",
     *     @OA\Schema(
     *          type="string",
     *          example="...456dfertwert345t...",
     *     )
     * ),
     *
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
