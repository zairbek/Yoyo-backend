<?php

namespace App\Containers\User\UI\API\Backoffice\Controllers;

use App\Containers\User\UI\API\Backoffice\Resources\AccountResource;
use App\Ship\Core\Abstracts\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AccountController extends ApiController
{

    /**
     * @OA\Get(
     *     path="/backoffice/v1/account",
     *     summary="Профиль",
     *     tags={"Backoffice.Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="appllication/json",
     *          )
     *     ),*
     *     @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Error: Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthenticated.",
     *              )
     *          ),
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Error: Forbidden",
     *          @OA\JsonContent(),
     *     )
     * ),
     *
     * @return JsonResponse
     */
    public function get()
    {
        $user = Auth::guard('api')->user();

        /**
         * OA\Schema(
         *     schema="getProfile",
         *     OA\Property(property="user", ref="#/components/schemas/UserProfileFullInfoTransformer"),
         *     OA\Property(property="role", ref="#/components/schemas/RoleMiniInfoTransformer"),
         *     OA\Property(
         *         property="permissions",
         *         OA\Items(ref="#/components/schemas/PermissionMiniInfoTransformer")),
         *     ),
         * )
         */
        return response()->json(new AccountResource($user));
    }
}
