<?php

namespace App\Containers\Authorization\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    protected static function newFactory(): RoleFactory
    {
        return new RoleFactory();
    }
}
