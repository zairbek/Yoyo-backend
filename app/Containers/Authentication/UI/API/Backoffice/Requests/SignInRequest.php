<?php

namespace App\Containers\Authentication\UI\API\Backoffice\Requests;

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
     *     schema="SignInRequest",
     *     required={"email", "password"},
     *     @OA\Property(
     *          property="email",
     *          type="string",
     *          example="test@test.ru",
     *     ),
     *     @OA\Property(
     *          property="password",
     *          type="string",
     *          example="123456",
     *     ),
     * )
     *
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ];
    }
}
