<?php

namespace App\Ship\Core\Abstracts\Structures;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JetBrains\PhpStorm\Pure;
use JsonException;
use JsonSerializable;
use ReflectionClass;

abstract class Structure implements Arrayable, Jsonable, JsonSerializable
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

    /**
     * @throws JsonException
     */
    public function toJson($options = 0): bool|string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR);
    }

    #[Pure] public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
