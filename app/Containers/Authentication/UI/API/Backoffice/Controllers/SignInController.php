<?php

namespace App\Containers\Authentication\UI\API\Backoffice\Controllers;

use App\Containers\Authentication\UI\API\Backoffice\Requests\SignInRequest;
use App\Ship\Core\Abstracts\Controllers\ApiController;
use App\Containers\Authentication\Adapters\Passport as PassportAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use JsonException;
use League\OAuth2\Server\Exception\OAuthServerException;

class SignInController extends ApiController
{
    /**
     * @param SignInRequest $request
     * @return JsonResponse|ValidationException
     * @throws ValidationException
     * @throws JsonException
     * @throws OAuthServerException
     */
    public function signIn(SignInRequest $request): JsonResponse|ValidationException
    {
        $clientCredentials = [
            'client_id' => $request->header('Client-Id'),
            'client_secret' => $request->header('Client-Secret'),
        ];

        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        if (! $this->attemptLogin($credentials)) {
            return $this->sendFailedLoginResponse($credentials);
        }

        $tokens = PassportAdapter::getTokenAndRefreshToken(array_merge($clientCredentials, $credentials));

        return $this->sendLoginResponse($tokens);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param array $credentials
     * @return bool
     */
    protected function attemptLogin(array $credentials): bool
    {
        return Auth::guard()->attempt($credentials);
    }

    /**
     * Get the failed login response instance.
     * @param array $request
     * @return ValidationException
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(array $request): ValidationException
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}
