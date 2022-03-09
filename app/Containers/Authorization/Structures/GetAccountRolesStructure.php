<?php

namespace App\Containers\Authorization\Structures;

use App\Containers\Authorization\Collections\PermissionsCollection;
use App\Containers\Authorization\Structures\Casts\RolesPermissionsStructureCast;
use App\Ship\Core\Abstracts\Structures\Structure;
use Spatie\DataTransferObject\Attributes\CastWith;

class GetAccountRolesStructure extends Structure
{
    public int $id;
    public string|null $title;
    public string $name;

    /** @var PermissionsCollection[] */
    #[CastWith(RolesPermissionsStructureCast::class)]
    public PermissionsCollection $permissions;
}
