<?php

namespace App\Containers\Authentication\Tasks;

use App\Containers\User\Models\User;
use App\Ship\Core\Abstracts\Tasks\Task;

class AttemptLoginWithPhoneAndCodeTask extends Task
{
    public function run(User $user, string $code): bool
    {
        if ($user->confirm_code && $code) {
            $user->confirm_code = null;
            $user->save();
            
            return true;
        }
        
        return false;
    }
}
