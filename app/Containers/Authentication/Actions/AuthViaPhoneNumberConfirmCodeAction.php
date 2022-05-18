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
        try {
            $user = app(FindUserByPhoneNumberTask::class)->run($request->get('phone'));

            if (!$user) {
                throw new RuntimeException('user not found');
            }

            if (! app(AttemptLoginWithPhoneAndCodeTask::class)->run($user, $request->get('code'))) {
                throw ValidationException::withMessages([
                    'phone' => [trans('auth.failed')],
                ]);
            }

            return app(GenerateTokensByUserAction::class)->run($user, $request->header('client-id'));
        } catch (\Exception $exception) {
            return [
                'status' => 'fail',
                'message' => $exception->getMessage()
            ];
        }
    }
}
