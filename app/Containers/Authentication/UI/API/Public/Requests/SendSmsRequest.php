<?php

namespace App\Containers\Authentication\UI\API\Public\Requests;

use App\Ship\Core\Abstracts\Requests\Request;

class SendSmsRequest extends Request
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
     *     schema="ClientSendSmsRequest",
     *     required={"phone"},
     *     @OA\Property(
     *          property="phone",
     *          type="string",
     *     ),
     * ),
     *  @OA\Schema(
     *     schema="ClientSendSmsRequestValidation",
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
     *     ),
     * )
     *
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'numeric', 'regex:/996[0-9]{9}/'],
        ];
    }
}
