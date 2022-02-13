<?php

namespace App\Containers\Authorization\Repositories;

use App\Containers\Authorization\Models\Role;
use App\Ship\Core\Abstracts\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class RoleRepository extends Repository
{
    public const DEFAULT_ROLE = 'user';

    protected function model(): string
    {
        return Role::class;
    }

    public function getDefaultRole(): Role
    {
        return $this->model()::findByName(self::DEFAULT_ROLE);
    }

    /**
     * @param string $title
     * @param string $name В латинских буквах
     * @return Role
     */
    public function create(string $title, string $name): Role
    {
        return $this->model()::create([
            'title' => $title,
            'name' => $name,
        ]);
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
            'name' => $this->query->where('name', 'like', "%$search%"),
            'title' => $this->query->where('title', 'like', "%$search%"),
            default => $this->query,
        };
    }

}
