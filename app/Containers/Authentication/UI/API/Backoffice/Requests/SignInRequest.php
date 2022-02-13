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
