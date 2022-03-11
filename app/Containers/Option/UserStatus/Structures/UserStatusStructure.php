<?php

namespace App\Containers\Option\UserStatus\Structures;

use App\Ship\Core\Abstracts\Structures\Structure;
use Serializable;

class UserStatusStructure extends Structure implements Serializable
{
    public string|null $title;
    public string $name;

    public function serialize()
    {
        return serialize($this->__serialize());
    }

    public function unserialize(string $data)
    {
        $data = unserialize($data);

        $this->title = $data['title'];
        $this->name = $data['name'];
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function __unserialize(array $data): void
    {
        $this->title = $data['title'];
        $this->name = $data['name'];
    }
}
