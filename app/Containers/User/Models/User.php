<?php

namespace App\Containers\User\Models;

use App\Containers\User\Enums\Gender;
use App\Containers\User\Enums\Status;
use App\Ship\Core\Abstracts\Models\UserModel;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $login
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $phone_number
 * @property string $avatar
 * @property Carbon $birthday
 * @property string $gender
 * @property string $status
 * @property array  $properties
 * @property Carbon $online_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends UserModel implements HasMedia
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;
    use HasRoles;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'email',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'phone_number',
        'avatar',
        'birthday',
        'gender',
        'status',
        'properties',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'active',
        'properties',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'online_at' => 'datetime',
        'birthday' => 'datetime:Y.m.d',
        'active' => 'boolean',
        'properties' => 'array',
        'gender' => Gender::class,
        'status' => Status::class,
    ];

    protected static function newFactory(): UserFactory
    {
        return new UserFactory();
    }

    public function password(): Attribute
    {
        return new Attribute(
            set: fn($value) => app(Hasher::class)->make($value)
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function avatar(): Attribute
    {
        return new Attribute(
            get: fn($value) => $this->load('media')->getMedia('avatar')->first(),
            set: fn($value) => $this->addMedia($value)->toMediaCollection('avatar')
        );
    }
}
