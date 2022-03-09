<?php

namespace App\Containers\Authorization\Structures\Casts;

use App\Containers\Authorization\Collections\RolesCollection;
use App\Containers\Authorization\Structures\GetAccountRolesStructure;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class RolesStructureCast implements Caster
{
    public function cast(mixed $value): RolesCollection
    {
        return new RolesCollection(array_map(
        /**
         * @throws UnknownProperties
         */ static fn (array $data) => new GetAccountRolesStructure(...$data),
            $value
        ));
    }
}
