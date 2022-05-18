<?php

namespace App\Containers\User\Tasks;

use App\Containers\User\Models\User;
use App\Containers\User\Repositories\UserRepository;
use App\Ship\Core\Abstracts\Tasks\Task;

class CreateUserWithOnlyPhoneNumberTask extends Task
{
    /**
     * @throws \Throwable
     */
    public function run(string $phoneNumber): User
    {
        return app(UserRepository::class)->createUserWithOnlyPhoneNumber($phoneNumber);
    }
}
