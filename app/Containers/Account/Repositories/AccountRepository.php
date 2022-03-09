<?php

namespace App\Containers\Account\Repositories;

use App\Containers\Account\Structures\GetAccountStructure;
use App\Containers\Authorization\Models\UserStatus;
use App\Containers\Authorization\Structures\GetAccountUserStatusStructure;
use App\Containers\User\Models\User;
use Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class AccountRepository
{
    private function authUser(): Authenticatable|User
    {
        $user = Auth::guard('api')->user();

        if (is_null($user)) {
            throw new RuntimeException('User not authorization', 401);
        }

        return $user;
    }

    public function prepareAuthUser(): User
    {
        $user = $this->authUser();

        return Cache::remember($user->id, 2, static function () use ($user) {
            return $user->load('roles.permissions', 'userStatus');
        });
    }

    public function getAuthUser()
    {
        $user = $this->prepareAuthUser()->toArray();
        $user['user_status'] = new GetAccountUserStatusStructure($user['user_status']);

        return new GetAccountStructure($user);
    }

    public function isAccountActive(): bool
    {
        $user = $this->prepareAuthUser();

        return ! is_null($user->userStatus)
            && $user->userStatus->name === UserStatus::ACTIVE;
    }

    public function isAccountBlocked(): bool
    {
        $user = $this->prepareAuthUser();

        return is_null($user->userStatus)
            || $user->userStatus->name === UserStatus::BLOCK;
    }
}
