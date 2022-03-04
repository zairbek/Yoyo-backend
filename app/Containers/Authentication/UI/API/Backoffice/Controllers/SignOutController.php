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
     * @OA\Get(
     *     path="/backoffice/v1/auth/sign-out",
     *     tags={ "Backoffice.Authentication" },
     *     summary="LogOut",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *          response=204,
     *          description="No Content",
     *          @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated.",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthenticated.",
     *              )
     *          ),
     *     ),
     * )
     *
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
