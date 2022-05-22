<?php

namespace App\Containers\Authentication\UI\API\Public\Controllers;

use App\Containers\Authentication\Actions\AuthViaPhoneNumberConfirmCodeAction;
use App\Containers\Authentication\Actions\AuthViaPhoneNumberSendSmsAction;
use App\Containers\Authentication\UI\API\Public\Requests\SendSmsRequest;
use App\Containers\Authentication\UI\API\Public\Requests\SignInRequest;
use App\Ship\Core\Abstracts\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class SignInController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/send",
     *     summary="Public | Авторизация | Отправка смс",
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
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(ref="#/components/schemas/ClientSendSmsRequest"),
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
     *          @OA\JsonContent(ref="#/components/schemas/ClientSendSmsRequestValidation"),
     *     ),
     * )
     *
     * @param SendSmsRequest $request
     * @return JsonResponse|ValidationException
     * @throws \Throwable
     */
    public function send(SendSmsRequest $request): JsonResponse|ValidationException
    {
        $res = app(AuthViaPhoneNumberSendSmsAction::class)->run($request->get('phone'));

        return Response::json($res);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/sign-in",
     *     summary="Public | Авторизация | Подтверждение кода",
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
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(ref="#/components/schemas/PublicSignInRequest"),
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
     *          @OA\JsonContent(ref="#/components/schemas/PublicSignInRequestValidation"),
     *     ),
     * )
     */
    public function signIn(SignInRequest $request)
    {
        $res = app(AuthViaPhoneNumberConfirmCodeAction::class)->run($request);

        return $this->sendLoginResponse($res);
    }
}
