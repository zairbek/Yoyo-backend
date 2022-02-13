<?php

namespace App\Containers\User\Repositories;

use App\Containers\Authorization\Repositories\RoleRepository;
use App\Containers\User\Models\User;
use App\Ship\Core\Abstracts\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserRepository extends Repository
{
    protected function model(): string
    {
        return User::class;
    }

    protected function afterMakeBuilder(): void
    {
        $this->query->with('roles');
    }

    /**
     * @param array $values
     * @param null $role
     * @return User
     * @throws Throwable
     */
    public function createUser(array $values, $role = null): User
    {
        DB::beginTransaction();
        try {
            $model = clone $this->model;
            /** @var User $user */
            $user = $model->fill($values);
            $user->save();
            $user = $user->refresh();

            if (is_null($role)) {
                $role = RoleRepository::DEFAULT_ROLE;
            }

            $user->assignRole($role);

            DB::commit();

            return $user;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    public function updateOrCreate($attributes, $value = [], $role = RoleRepository::DEFAULT_ROLE): Model
    {
        /** @var User $user */
        $user = parent::updateOrCreate($attributes, $value);
        $user->assignRole($role);

        return $user;
    }

    /**
     * @param null $field
     * @param null $search
     * @return Builder
     */
    public function search($field = null, $search = null): Builder
    {
        if (! $field && ! $search) {
            return $this->query;
        }

        return match ($field) {
            'id' => $this->query->where('id', $search),
            'name' => $this->query
                ->where(DB::raw(
                    "LOWER(REPLACE(CONCAT(
                                    COALESCE(first_name,''),' ',
                                    COALESCE(last_name,''),' ',
                                    COALESCE(second_name,'')
                                ),
                            '  ',' '))"
                ), 'like', '%' . strtolower($search) . '%'),
            'email' => $this->query->where('email', 'like', "%$search%"),
            'roles' => $this->query->whereHas('roles', function (Builder $builder) use ($search) {
                return $builder->where('title', 'like', "%$search%");
            }),
            default => $this->query,
        };
    }

}
