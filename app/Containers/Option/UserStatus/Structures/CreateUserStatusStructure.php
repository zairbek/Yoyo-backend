<?php

namespace App\Containers\Option\UserStatus\Structures;

use App\Containers\User\Models\User;
use App\Ship\Core\Abstracts\Structures\Structure;

class CreateUserStatusStructure extends Structure
{
    public User $user;
    public string $name;
    public string $value;
}
