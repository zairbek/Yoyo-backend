<?php

namespace App\Ship\Core\Abstracts\Structures;

use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;

abstract class Structure implements Arrayable
{
    public function __construct(array $data)
    {
        $reflectionClass = new ReflectionClass(static::class);

        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isPublic()) {
                $this->{$property->getName()} = $data[$property->getName()];
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
