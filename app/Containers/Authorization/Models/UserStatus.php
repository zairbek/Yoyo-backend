<?php

namespace App\Containers\Authorization\Models;

use App\Ship\Core\Abstracts\Models\Model;

class UserStatus extends Model
{
    protected $fillable = [
        'title',
        'name',
        'description',
        'properties',
    ];
}
