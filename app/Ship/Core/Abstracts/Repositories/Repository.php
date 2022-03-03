<?php

namespace App\Ship\Core\Abstracts\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use RuntimeException;

abstract class Repository
{
    public Model $model;
    public Builder $query;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->makeModel();
        $this->makeBuilder();
        $this->afterMakeBuilder();
    }

    /**
     * Defining model class
     * @return string
     */
    abstract protected function model(): string;

    /**
     * @return Model
     * @throws Exception
     */
    private function makeModel(): Model
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new RuntimeException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    protected function afterMakeBuilder(): void
    {
        //
    }

    protected function makeBuilder(): Builder
    {
        return $this->query = $this->model->newQuery();
    }

    /**
     * @param $attributes
     * @param array $value
     * @return Model
     */
    public function updateOrCreate($attributes, array $value = []): Model
    {
        return $this->model()::updateOrCreate(
            $attributes,
            $value
        );
    }

    /**
     * @param string $field
     * @param null $value
     * @param string[] $columns
     * @return Collection
     */
    public function findByField(string $field, $value = null, array $columns = ['*']): Collection
    {
        return $this->query->where($field, '=', $value)->get($columns);
    }

    public function all($columns = ['*']): Collection
    {
        return $this->query->get($columns);
    }

    /**
     * @param int|null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function paginate(
        ?int $perPage = null,
        array $columns = ['*'],
        string $pageName = 'page',
        ?int $page = null
    ): LengthAwarePaginator {
        return $this->query->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param array $values
     * @param $uniqueBy
     * @param null $update
     * @return int
     */
    public function upsert(array $values, $uniqueBy, $update = null): int
    {
        return $this->query->upsert($values, $uniqueBy, $update);
    }
}
