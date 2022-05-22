<?php

namespace App\Containers\Authentication\UI\API\Public\Controllers;

use App\Containers\Authentication\Adapters\Passport as PassportAdaptor;
use App\Ship\Core\Abstracts\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;
use League\OAuth2\Server\Exception\OAuthServerException;

class RefreshController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/refresh-token",
     *     summary="Public | Refresh access token & refresh token",
     *     tags={"Public.Authentication"},
     *     @OA\Parameter(
     *          required=true,
     *          in="header",
     *          name="client-id",
     *          @OA\Schema(type="string", example=""),
     *     ),
     *     @OA\Parameter(
     *          required=true,
     *          in="header",
     *          name="client-secret",
     *          @OA\Schema(type="string", example=""),
     *     ),
     *     @OA\Parameter(
     *          in="cookie",
     *          name="refresh-token",
     *          required=true,
     *          @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(ref="#/components/schemas/AuthResponse"),
     *          @OA\Header(header="cookies", ref="#/components/headers/RefreshToken")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="The refresh token is invalid.",
     *     ),
     * ),
     *
     * @param Request $request
     * @return JsonResponse
     * @throws JsonException
     * @throws OAuthServerException
     */
    public function refreshToken(Request $request): JsonResponse
    {
        // Валидация идет в мидлваре
        $clientCredentials = [
            'client_id' => $request->header('client-id'),
            'client_secret' => $request->header('client-secret'),
        ];

        $tokens = PassportAdaptor::generateRefreshToken(
            $clientCredentials,
            $request->cookie('refresh-token')
        );

        return $this->sendLoginResponse($tokens);
    }
}
