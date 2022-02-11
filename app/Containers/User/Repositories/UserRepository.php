<?php

namespace App\Containers\User\Repositories;

use App\Containers\User\Models\User;
use App\Ship\Core\Abstracts\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository
{
    protected function model(): string
    {
        return User::class;
    }

    public function updateOrCreate($attributes, $value = [], $role = null): Model
    {
        /** @var User $user */
        $user = parent::updateOrCreate($attributes, $value);

//        if (is_null($role)) {
//            $role = RoleRepository::DEFAULT_ROLE;
//        }
//        $user->assignRole($role);

        return $user;
    }
}
