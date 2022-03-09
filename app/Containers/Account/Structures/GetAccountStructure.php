<?php

namespace App\Containers\Account\Structures;

use App\Containers\Authorization\Collections\RolesCollection;
use App\Containers\Authorization\Structures\Casts\RolesStructureCast;
use App\Containers\Authorization\Structures\GetAccountUserStatusStructure;
use App\Ship\Core\Abstracts\Structures\Structure;
use Spatie\DataTransferObject\Attributes\CastWith;

class GetAccountStructure extends Structure
{
    public int $id;
    public string $login;
    public string $email;
    public string|null $first_name;
    public string|null $last_name;
    public string|null $middle_name;
    public string|null $phone_number;
    public string|null $avatar;
    public string|null $birthday;
    public string|null $gender;

    /** @var RolesCollection[] */
    #[CastWith(RolesStructureCast::class)]
    public RolesCollection $roles;
    public GetAccountUserStatusStructure $user_status;
}
