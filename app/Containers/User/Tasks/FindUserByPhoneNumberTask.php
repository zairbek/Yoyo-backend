<?php

namespace App\Containers\User\Tasks;

use App\Containers\User\Models\User;
use App\Containers\User\Repositories\UserRepository;
use App\Ship\Core\Abstracts\Models\Model;
use App\Ship\Core\Abstracts\Tasks\Task;

class FindUserByPhoneNumberTask extends Task
{
    public function run(string $phoneNumber): null|User|Model
    {
        return app(UserRepository::class)->getByPhoneNumber($phoneNumber);
    }
}
