<?php

namespace App\Containers\Account\UI\API\Backoffice\Controllers;

use App\Containers\Account\Repositories\AccountRepository;
use App\Ship\Core\Abstracts\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

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
    public function get(AccountRepository $repository)
    {
        $user = $repository->getAuthUser();
        /**
         * OA\Schema(
         *     schema="getProfile",
         *     OA\Property(property="user", ref="#/components/schemas/UserProfileFullInfoTransformer"),
         *     OA\Property(property="role", ref="#/components/schemas/RoleMiniInfoTransformer"),
         *     OA\Property(
         *         property="permissions",
         *         OA\Items(ref="#/components/schemas/PermissionMiniInfoTransformer")),
         *     )
         * )
         */
        return new JsonResponse($user->toArray());
    }
}
