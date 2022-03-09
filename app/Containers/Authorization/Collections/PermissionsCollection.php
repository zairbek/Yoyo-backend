<?php

namespace App\Containers\Authorization\Collections;

use App\Containers\Authorization\Structures\GetAccountRolesPermissionStructure;
use Illuminate\Support\Collection;

class PermissionsCollection extends Collection
{
    // Add the correct return type here for static analyzers to know which type of array this is
    public function offsetGet($key): GetAccountRolesPermissionStructure
    {
        return parent::offsetGet($key);
    }
}
