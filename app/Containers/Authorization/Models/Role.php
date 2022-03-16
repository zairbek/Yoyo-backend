<?php

namespace App\Containers\Authorization\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * @property Collection $permissions
 */
class Role extends SpatieRole
{
    use HasFactory;

    protected $hidden = ['pivot'];

    protected static function newFactory(): RoleFactory
    {
        return new RoleFactory();
    }
}
