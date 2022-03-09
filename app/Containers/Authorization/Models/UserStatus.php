<?php

namespace App\Containers\Authorization\Models;

use App\Containers\User\Models\User;
use App\Ship\Core\Abstracts\Models\Model;
use Database\Factories\UserStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $title
 * @property string $name
 * @property string $description
 * @property array $properties
 * 
 * @property Collection $users
 */
class UserStatus extends Model
{
    use HasFactory;
    
    public const ACTIVE = 'active';
    public const BLOCK = 'block';
    
    protected $fillable = [
        'title',
        'name',
        'description',
        'properties',
    ];

    protected static function newFactory(): UserStatusFactory
    {
        return new UserStatusFactory();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
