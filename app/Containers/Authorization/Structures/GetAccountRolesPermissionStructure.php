<?php

namespace App\Containers\Authorization\Structures;

use App\Ship\Core\Abstracts\Structures\Structure;

class GetAccountRolesPermissionStructure extends Structure
{
    public int $id;
    public string|null $title;
    public string $name;
}
