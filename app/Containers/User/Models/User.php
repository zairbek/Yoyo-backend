<?php

namespace App\Containers\User\Models;

use App\Containers\Authorization\Models\UserStatus;
use App\Containers\User\Enums\Gender;
use App\Ship\Core\Abstracts\Models\UserModel;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property UserStatus $userStatus
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
        'active',
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
        'gender' => Gender::class
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

    public function userStatus(): BelongsTo
    {
        return $this->belongsTo(UserStatus::class);
    }
}
