<?php

namespace App\Ship\Providers;

use App\Containers\Authorization\Models\Permission;
use App\Containers\Authorization\Models\Role;
use App\Containers\Option\Models\Option;
use App\Containers\User\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ModelServiceProvider extends ServiceProvider
{
    public const MAPPING = [
        User::class => 'users',
        Role::class => 'roles',
        Permission::class => 'permissions',
        Media::class => 'media',
        Option::class => 'option',
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Когда мы используем полиморфные связи, в колонку model_type записывается вот так '\Namespace\Classname'
     * Ниже мы переопределяем это. И теперь, если мы даже хотим переопределить класс (наследоваться), то нам нужно просто поменять в конфиге классы
     * @url https://laravel.com/docs/8.x/eloquent-relationships#custom-polymorphic-types
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap(array_flip(self::MAPPING));
    }
}
