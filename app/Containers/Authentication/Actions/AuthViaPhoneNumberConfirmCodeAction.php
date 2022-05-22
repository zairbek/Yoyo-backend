<?php

namespace App\Containers\Authentication\Actions;

use App\Containers\Authentication\Tasks\AttemptLoginWithPhoneAndCodeTask;
use App\Containers\Authentication\UI\API\Public\Requests\SignInRequest;
use App\Containers\User\Tasks\FindUserByPhoneNumberTask;
use App\Ship\Core\Abstracts\Actions\Action;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class AuthViaPhoneNumberConfirmCodeAction extends Action
{
    /**
     * @throws Throwable
     */
    public function run(SignInRequest $request): array
    {
        $user = app(FindUserByPhoneNumberTask::class)->run($request->get('phone'));

        if (! $user) {
            $this->validationMessage();
        }

        if (! app(AttemptLoginWithPhoneAndCodeTask::class)->run($user, $request->get('code'))) {
            $this->validationMessage();
        }

        return app(GenerateTokensByUserAction::class)->run($user, $request->header('client-id'));
    }

    /**
     * @throws ValidationException
     */
    private function validationMessage(): ValidationException
    {
        return throw ValidationException::withMessages([
            'phone' => [trans('auth.failed')],
        ]);
    }
}
