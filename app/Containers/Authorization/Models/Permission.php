<?php

namespace App\Containers\Authorization\Models;

use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;

    protected $hidden = ['pivot'];

    protected static function newFactory(): PermissionFactory
    {
        return new PermissionFactory();
    }
}
