<?php

namespace App\Containers\Authentication\Tasks;

use App\Containers\User\Models\User;
use App\Containers\User\Repositories\UserRepository;
use App\Ship\Core\Abstracts\Tasks\Task;

class SendAuthSmsCodeTask extends Task
{
    public function run(User $user)
    {
        $code = app(UserRepository::class)->createSmsCode($user);

        return true;
    }
}
