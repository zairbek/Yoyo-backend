<?php

namespace App\Containers\Authorization\Collections;

use App\Containers\Authorization\Structures\GetAccountRolesStructure;
use Illuminate\Support\Collection;

class RolesCollection extends Collection
{
    // Add the correct return type here for static analyzers to know which type of array this is
    public function offsetGet($key): GetAccountRolesStructure
    {
        return parent::offsetGet($key);
    }
}
