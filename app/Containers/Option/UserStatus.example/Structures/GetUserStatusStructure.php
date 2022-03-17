<?php

namespace App\Containers\Option\UserStatus\Structures;

use App\Ship\Core\Abstracts\Structures\Structure;

class GetUserStatusStructure extends Structure
{
    public string $name;
    public string $value;
}
