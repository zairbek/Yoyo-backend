<?php

namespace App\Containers\Authentication\Actions;

use App\Containers\Authentication\Tasks\SendAuthSmsCodeTask;
use App\Containers\User\Tasks\CreateUserWithOnlyPhoneNumberTask;
use App\Containers\User\Tasks\FindUserByPhoneNumberTask;
use App\Ship\Core\Abstracts\Actions\Action;

class AuthViaPhoneNumberSendSmsAction extends Action
{
    /**
     * @throws \Throwable
     */
    public function run(string $phone): array
    {
        try {
            $user = app(FindUserByPhoneNumberTask::class)->run($phone);

            if (! $user) {
                $user = app(CreateUserWithOnlyPhoneNumberTask::class)->run($phone);
            }

            app(SendAuthSmsCodeTask::class)->run($user);

            return [
                'status' => 'ok',
                //'message' => '',
                // времменно
                'message' => $user->refresh()->confirm_code
            ];
        } catch (\Exception $exception) {
            return [
                'status' => 'fail',
                'message' => $exception->getMessage()
            ];
        }
    }
}
