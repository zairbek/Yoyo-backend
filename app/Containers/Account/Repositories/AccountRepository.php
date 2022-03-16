<?php

namespace App\Containers\Account\Repositories;

use App\Containers\Account\Structures\GetAccountStructure;
use App\Containers\User\Enums\Status;
use App\Containers\User\Models\User;
use App\Ship\Core\Abstracts\Structures\Structure;
use Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
            return $user->load(['roles' => static function (MorphToMany $query) {
                $query->with(['permissions' => static function (BelongsToMany $query) {
                    $query->select(['id', 'name', 'title']);
                }])->select(['id', 'name', 'title']);
            }]);
        });
    }

    /**
     */
    public function getAuthUser(): Structure
    {
        $user = $this->prepareAuthUser()->append(['avatar']);

        return new GetAccountStructure($user->toArray());
    }

    public function isAccountActive(): bool
    {
        $user = $this->prepareAuthUser();

        return $user->status === Status::Active;
    }

    public function isAccountBlocked(): bool
    {
        $user = $this->prepareAuthUser();

        return $user->status === Status::Block;
    }
}
