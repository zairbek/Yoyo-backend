<?php

namespace App\Containers\Authentication\UI\API\Public\Requests;

use App\Ship\Core\Abstracts\Requests\Request;

class SignInRequest extends Request
{
    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     *  @OA\Schema(
     *     schema="PublicSignInRequest",
     *     required={"phone", "code"},
     *     @OA\Property(
     *          property="phone",
     *          type="string",
     *          example="996772119663",
     *     ),
     *     @OA\Property(
     *          property="code",
     *          type="string",
     *          example="1234",
     *     ),
     * )
     * @OA\Schema(
     *     schema="PublicSignInRequestValidation",
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="The given data was invalid.",
     *     ),
     *     @OA\Property(
     *         property="errors",
     *         @OA\Property(
     *             property="phone",
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 example="These credentials do not match our records.",
     *             ),
     *         ),
     *         @OA\Property(
     *             property="code",
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *                 example="These credentials do not match our records.",
     *             ),
     *         ),
     *     ),
     * )
     *
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'numeric', 'regex:/996[0-9]{9}/', 'exists:users,phone_number'],
            'code' => ['required', 'string']
        ];
    }
}
