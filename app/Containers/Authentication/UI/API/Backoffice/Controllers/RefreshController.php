<?php

namespace App\Containers\Authentication\UI\API\Backoffice\Controllers;

use App\Containers\Authentication\Adapters\Passport as PassportAdaptor;
use App\Ship\Core\Abstracts\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;
use League\OAuth2\Server\Exception\OAuthServerException;

class RefreshController extends ApiController
{
    /**
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
