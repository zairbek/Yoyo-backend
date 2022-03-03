<?php

namespace App\Containers\Authorization\Repositories;

use App\Containers\Authorization\Models\UserStatus;
use App\Ship\Core\Abstracts\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

class UserStatusRepository extends Repository
{
    public const DEFAULT_STATUS = 'user';

    protected function model(): string
    {
        return UserStatus::class;
    }

    public function getDefaultRole(): UserStatus
    {
        return $this->model()::findByName(self::DEFAULT_STATUS);
    }

    /**
     * @param string $title
     * @param string $name В латинских буквах
     * @return UserStatus
     */
    public function create(string $title, string $name): UserStatus
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
