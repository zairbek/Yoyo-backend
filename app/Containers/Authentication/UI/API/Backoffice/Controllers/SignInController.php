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
     *  @OA\Post(
     *     path="backoffice/v1/auth/sign-in",
     *     summary="Авторизация",
     *     tags={"Backoffice.Authentication"},
     *     operationId="signIn",
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
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(ref="#/components/schemas/SignInRequest"),
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(ref="#/components/schemas/AuthResponse"),
     *          @OA\Header(header="cookies", ref="#/components/headers/RefreshToken"),
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="The given data was invalid.",
     *              ),
     *              @OA\Property(
     *                  property="errors",
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(
     *                          type="string",
     *                          example="These credentials do not match our records.",
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="array",
     *                      @OA\Items(
     *                          type="string",
     *                          example="The password field is required.",
     *                      ),
     *                  ),
     *              ),
     *          ),
     *     ),
     * )
     *
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
