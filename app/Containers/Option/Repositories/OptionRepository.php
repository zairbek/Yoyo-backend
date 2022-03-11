<?php

namespace App\Containers\Option\Repositories;

use App\Containers\Option\Models\Option;
use App\Ship\Core\Abstracts\Repositories\Repository;

class OptionRepository extends Repository
{
    /**
     * @inheritDoc
     */
    protected function model(): string
    {
        return Option::class;
    }
}
