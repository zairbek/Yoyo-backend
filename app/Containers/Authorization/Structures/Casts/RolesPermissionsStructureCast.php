<?php

namespace App\Containers\Authorization\Structures\Casts;

use App\Containers\Authorization\Collections\PermissionsCollection;
use App\Containers\Authorization\Structures\GetAccountRolesPermissionStructure;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class RolesPermissionsStructureCast implements Caster
{
    public function cast(mixed $value): PermissionsCollection
    {
        return new PermissionsCollection(array_map(
        /**
         * @throws UnknownProperties
         */ static fn (array $data) => new GetAccountRolesPermissionStructure(...$data),
            $value
        ));
    }
}
