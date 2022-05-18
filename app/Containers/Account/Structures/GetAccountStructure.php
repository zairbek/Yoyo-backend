<?php

namespace App\Containers\Account\Structures;

use App\Ship\Core\Abstracts\Structures\Structure;

class GetAccountStructure extends Structure
{
    public int $id;
    public string|null $login;
    public string|null $email;
    public string|null $first_name;
    public string|null $last_name;
    public string|null $middle_name;
    public string|null $phone_number;
    public string|null $avatar;
    public string|null $birthday;
    public string|null $gender;
    public string $status;

    public array $roles;
}
